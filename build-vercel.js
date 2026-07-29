const fs = require('fs');
const path = require('path');
const libphp = require('@libphp/almalinux-9-v85');

// Monkey-patch libphp.getFiles to ensure Linux forward slashes on Windows
const origGetFiles = libphp.getFiles;
libphp.getFiles = async function() {
    const files = await origGetFiles.call(this);
    const normalized = {};
    for (const [k, v] of Object.entries(files)) {
        normalized[k.replace(/\\/g, '/')] = v;
    }
    return normalized;
};

const vercelPhp = require('vercel-php');

async function main() {
    console.log('🚀 Starting custom Vercel Build Output API v3 generator...');
    const workPath = process.cwd();

    // 1. Run vercel-php builder
    const result = await vercelPhp.build({
        files: {},
        entrypoint: 'api/index.php',
        workPath: workPath,
        config: {},
        meta: { isDev: false }
    });

    const funcDir = path.join(workPath, '.vercel/output/functions/api/index.func');
    fs.mkdirSync(funcDir, { recursive: true });

    // 2. Copy libphp binaries (including php-cgi, php, and shared libraries)
    console.log('📦 Writing libphp binaries to .vercel/output...');
    const libphpFiles = await libphp.getFiles();
    for (const [relPath, fileObj] of Object.entries(libphpFiles)) {
        const destPath = path.join(funcDir, relPath);
        fs.mkdirSync(path.dirname(destPath), { recursive: true });
        if (fileObj.fsPath && fs.existsSync(fileObj.fsPath)) {
            try {
                const realPath = fs.realpathSync(fileObj.fsPath);
                fs.copyFileSync(realPath, destPath);
            } catch(e) {
                try { fs.copyFileSync(fileObj.fsPath, destPath); } catch(err) {}
            }
        } else if (fileObj.data) {
            fs.writeFileSync(destPath, fileObj.data);
        }
        try { fs.chmodSync(destPath, 0o755); } catch (e) {}
    }

    // 3. Copy user lambda files
    console.log('📦 Writing user function files to .vercel/output...');
    const lambdaFiles = result.output.files;
    for (const [relPath, fileObj] of Object.entries(lambdaFiles)) {
        const normalizedPath = relPath.replace(/\\/g, '/');

        // Ignore git, node_modules, and vercel cache
        if (
            normalizedPath.startsWith('user/.git/') ||
            normalizedPath.startsWith('user/node_modules/') ||
            normalizedPath.startsWith('user/.vercel/')
        ) {
            continue;
        }

        const destPath = path.join(funcDir, normalizedPath);
        fs.mkdirSync(path.dirname(destPath), { recursive: true });

        if (fileObj.fsPath) {
            if (fs.existsSync(fileObj.fsPath)) {
                try {
                    const realPath = fs.realpathSync(fileObj.fsPath);
                    const stat = fs.statSync(realPath);
                    if (stat.isDirectory()) {
                        fs.cpSync(realPath, destPath, { recursive: true, dereference: true });
                    } else {
                        fs.copyFileSync(realPath, destPath);
                    }
                } catch(e) {
                    try {
                        fs.copyFileSync(fileObj.fsPath, destPath);
                    } catch(err) {}
                }
            }
        } else if (fileObj.data) {
            fs.writeFileSync(destPath, fileObj.data);
        }
        if (fileObj.mode) {
            try { fs.chmodSync(destPath, fileObj.mode); } catch (e) {}
        }
    }

    // 3. Create index.js wrapper using stateless PHP-CGI runner (no port 3000 server, no hanging)
    console.log('🔧 Creating index.js entrypoint wrapper with PHP-CGI runner...');
    const indexJsContent = `
const { execFile } = require('child_process');
const path = require('path');
const fs = require('fs');

if (!process.env.LAMBDA_TASK_ROOT) process.env.LAMBDA_TASK_ROOT = '/var/task';

module.exports = async function launcher(event, context) {
    return new Promise((resolve) => {
        try {
            const taskRoot = process.env.LAMBDA_TASK_ROOT || '/var/task';
            const phpCgiBin = path.join(taskRoot, 'php/php-cgi');
            const phpIni = path.join(taskRoot, 'php/php.ini');
            const scriptFile = path.join(taskRoot, 'user/api/index.php');

            const reqUrl = event.url || event.rawPath || event.path || '/';
            const [reqPath, queryString] = reqUrl.split('?');
            const method = (event.method || event.httpMethod || (event.requestContext && event.requestContext.http && event.requestContext.http.method) || 'GET').toUpperCase();
            const headers = event.headers || {};

            const env = {
                ...process.env,
                GATEWAY_INTERFACE: 'CGI/1.1',
                SERVER_PROTOCOL: 'HTTP/1.1',
                REQUEST_METHOD: method,
                SCRIPT_FILENAME: scriptFile,
                SCRIPT_NAME: '/index.php',
                PATH_INFO: reqPath || '/',
                REQUEST_URI: reqUrl,
                QUERY_STRING: queryString || '',
                HTTP_HOST: headers.host || headers.Host || 'localhost',
                REDIRECT_STATUS: '200',
                LD_LIBRARY_PATH: \`\${path.join(taskRoot, 'lib')}:\${process.env.LD_LIBRARY_PATH || ''}\`
            };

            for (const [key, val] of Object.entries(headers)) {
                const headerKey = 'HTTP_' + key.toUpperCase().replace(/-/g, '_');
                env[headerKey] = val;
            }

            let inputBuffer = Buffer.alloc(0);
            if (event.body) {
                inputBuffer = event.isBase64Encoded ? Buffer.from(event.body, 'base64') : Buffer.from(event.body);
                env.CONTENT_LENGTH = String(inputBuffer.length);
                if (headers['content-type']) env.CONTENT_TYPE = headers['content-type'];
            }

            const child = execFile(phpCgiBin, ['-c', phpIni], {
                env,
                cwd: path.join(taskRoot, 'user'),
                maxBuffer: 20 * 1024 * 1024,
                encoding: 'buffer',
                timeout: 10000
            }, (err, stdout, stderr) => {
                if (stderr && stderr.length > 0) {
                    console.log('PHP CGI stderr:', stderr.toString('utf8'));
                }

                if (err && (!stdout || stdout.length === 0)) {
                    console.error('PHP CGI exec error:', err);
                    return resolve({
                        statusCode: 500,
                        headers: { 'content-type': 'text/plain' },
                        body: Buffer.from('PHP CGI Error: ' + err.message).toString('base64'),
                        isBase64Encoded: true
                    });
                }

                const rawOutput = stdout || Buffer.alloc(0);

                let headerEndIndex = rawOutput.indexOf('\\r\\n\\r\\n');
                let headerLen = 4;
                if (headerEndIndex === -1) {
                    headerEndIndex = rawOutput.indexOf('\\n\\n');
                    headerLen = 2;
                }

                if (headerEndIndex === -1) {
                    return resolve({
                        statusCode: 200,
                        headers: { 'content-type': 'text/html; charset=UTF-8' },
                        body: rawOutput.toString('base64'),
                        isBase64Encoded: true
                    });
                }

                const headerPart = rawOutput.slice(0, headerEndIndex).toString('utf8');
                const bodyPart = rawOutput.slice(headerEndIndex + headerLen);

                const responseHeaders = {};
                let statusCode = 200;

                headerPart.split(/\\r?\\n/).forEach(line => {
                    const colonIndex = line.indexOf(':');
                    if (colonIndex !== -1) {
                        const key = line.slice(0, colonIndex).trim().toLowerCase();
                        const val = line.slice(colonIndex + 1).trim();
                        if (key === 'status') {
                            statusCode = parseInt(val, 10) || 200;
                        } else {
                            responseHeaders[key] = val;
                        }
                    }
                });

                resolve({
                    statusCode,
                    headers: responseHeaders,
                    body: bodyPart.toString('base64'),
                    isBase64Encoded: true
                });
            });

            if (inputBuffer.length > 0) {
                child.stdin.write(inputBuffer);
            }
            child.stdin.end();
        } catch (err) {
            console.error('Launcher Exception:', err);
            resolve({
                statusCode: 500,
                headers: { 'content-type': 'text/plain' },
                body: Buffer.from('Launcher Exception: ' + (err.stack || err)).toString('base64'),
                isBase64Encoded: true
            });
        }
    });
};
`;
    fs.writeFileSync(path.join(funcDir, 'index.js'), indexJsContent);

    // 4. Create .vc-config.json
    const vcConfig = {
        runtime: "nodejs20.x",
        handler: "index.js",
        launcherType: "Nodejs",
        environment: {
            LAMBDA_TASK_ROOT: "/var/task",
            NOW_ENTRYPOINT: "api/index.php",
            NOW_PHP_DEV: "0"
        }
    };
    fs.writeFileSync(path.join(funcDir, '.vc-config.json'), JSON.stringify(vcConfig, null, 2));

    // 5. Create .vercel/output/config.json
    const outputConfig = {
        version: 3,
        routes: [
            { src: "/build/(.*)", dest: "/public/build/$1" },
            { src: "/assets/(.*)", dest: "/public/assets/$1" },
            { src: "/(css|js|images|storage)/(.*)", dest: "/public/$1/$2" },
            { src: "/(.*)", dest: "/api/index" }
        ]
    };
    fs.writeFileSync(path.join(workPath, '.vercel/output/config.json'), JSON.stringify(outputConfig, null, 2));

    console.log('✅ Successfully generated Vercel Build Output API v3 bundle!');
}

main().catch(err => {
    console.error('❌ Failed to generate vercel output:', err);
    process.exit(1);
});
