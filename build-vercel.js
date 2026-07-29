const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');
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
        
        const sourcePath = typeof fileObj === 'string' ? fileObj : (fileObj.fsPath || null);
        const fileData = typeof fileObj === 'string' ? null : (fileObj.data || null);

        if (sourcePath && fs.existsSync(sourcePath)) {
            try {
                const realPath = fs.realpathSync(sourcePath);
                fs.copyFileSync(realPath, destPath);
            } catch(e) {
                try { fs.copyFileSync(sourcePath, destPath); } catch(err) {}
            }
        } else if (fileData) {
            fs.writeFileSync(destPath, fileData);
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
    console.log('📦 Copying shared libraries to php directory to satisfy dynamic linker...');
    const libDir = path.join(funcDir, 'lib');
    const phpDir = path.join(funcDir, 'php');
    if (fs.existsSync(libDir)) {
        const libs = fs.readdirSync(libDir);
        for (const lib of libs) {
            try {
                fs.copyFileSync(path.join(libDir, lib), path.join(phpDir, lib));
            } catch(e) {}
        }
    }

    console.log('📦 Running composer install in user directory...');
    try {
        const phpCli = path.join(funcDir, 'php/php');
        const composerCli = path.join(funcDir, 'php/composer');
        const phpIniPath = path.join(funcDir, 'php/php.ini');
        
        let phpIniContent = fs.readFileSync(phpIniPath, 'utf8');
        phpIniContent = phpIniContent.replace('extension_dir=/var/task/php/modules', 'extension_dir=' + path.join(funcDir, 'php/modules'));
        const buildPhpIniPath = path.join(funcDir, 'php/php-build.ini');
        fs.writeFileSync(buildPhpIniPath, phpIniContent);

        execFileSync(phpCli, [
            '-c', buildPhpIniPath,
            composerCli,
            'install',
            '--no-dev',
            '--optimize-autoloader'
        ], {
            cwd: path.join(funcDir, 'user'),
            env: Object.assign({}, process.env, {
                LD_LIBRARY_PATH: path.join(funcDir, 'lib') + (process.env.LD_LIBRARY_PATH ? ':' + process.env.LD_LIBRARY_PATH : '')
            }),
            stdio: 'inherit'
        });
        console.log('✅ Composer install successful!');
    } catch (e) {
        console.error('❌ Composer install failed:', e);
    }

    // 4. Create index.js wrapper using synchronous PHP-CGI (req, res) handler
    console.log('🔧 Creating index.js entrypoint wrapper with (req, res) PHP-CGI runner...');
    const indexJsContent = "const { execFileSync } = require('child_process');\n" +
"const path = require('path');\n" +
"const fs = require('fs');\n" +
"\n" +
"module.exports = async (req, res) => {\n" +
"    try {\n" +
"        const taskRoot = __dirname;\n" +
"        const phpCgiBin = path.join(taskRoot, 'php/php-cgi');\n" +
"        const phpIni = path.join(taskRoot, 'php/php.ini');\n" +
"        \n" +
"        let scriptFile = path.join(taskRoot, 'user/api/index.php');\n" +
"        if (!fs.existsSync(scriptFile)) {\n" +
"            scriptFile = path.join(taskRoot, 'user/public/index.php');\n" +
"        }\n" +
"\n" +
"        const reqUrl = req.url || '/';\n" +
"        const [reqPath, queryString] = reqUrl.split('?');\n" +
"        const method = (req.method || 'GET').toUpperCase();\n" +
"\n" +
"        if (reqUrl === '/ls') {\n" +
"            res.statusCode = 200;\n" +
"            res.setHeader('content-type', 'text/plain');\n" +
"            let out = 'NODE JS IS RUNNING\\n';\n" +
"            return res.end(out);\n" +
"        }\n" +
"\n" +
"        const env = {\n" +
"            ...process.env,\n" +
"            GATEWAY_INTERFACE: 'CGI/1.1',\n" +
"            SERVER_PROTOCOL: 'HTTP/1.1',\n" +
"            REQUEST_METHOD: method,\n" +
"            SCRIPT_FILENAME: scriptFile,\n" +
"            SCRIPT_NAME: '/index.php',\n" +
"            PATH_INFO: reqPath || '/',\n" +
"            REQUEST_URI: reqUrl,\n" +
"            QUERY_STRING: queryString || '',\n" +
"            HTTP_HOST: req.headers.host || req.headers.Host || 'localhost',\n" +
"            REDIRECT_STATUS: '200',\n" +
"            LD_LIBRARY_PATH: path.join(taskRoot, 'lib') + ':' + (process.env.LD_LIBRARY_PATH || '')\n" +
"        };\n" +
"\n" +
"        for (const [key, val] of Object.entries(req.headers || {})) {\n" +
"            const headerKey = 'HTTP_' + key.toUpperCase().replace(/-/g, '_');\n" +
"            env[headerKey] = val;\n" +
"        }\n" +
"        if (req.headers['content-type']) env.CONTENT_TYPE = req.headers['content-type'];\n" +
"        if (req.headers['content-length']) env.CONTENT_LENGTH = req.headers['content-length'];\n" +
"\n" +
"        // Collect body manually if needed\n" +
"        let inputBuffer = Buffer.alloc(0);\n" +
"        if (method !== 'GET' && method !== 'HEAD') {\n" +
"            if (req.body) {\n" +
"                if (Buffer.isBuffer(req.body)) {\n" +
"                    inputBuffer = req.body;\n" +
"                } else if (typeof req.body === 'string') {\n" +
"                    inputBuffer = Buffer.from(req.body);\n" +
"                } else {\n" +
"                    // If Vercel parsed it as an object, serialize it back based on content type\n" +
"                    const ct = req.headers['content-type'] || '';\n" +
"                    if (ct.includes('application/json')) {\n" +
"                        inputBuffer = Buffer.from(JSON.stringify(req.body));\n" +
"                    } else if (ct.includes('application/x-www-form-urlencoded')) {\n" +
"                        inputBuffer = Buffer.from(new URLSearchParams(req.body).toString());\n" +
"                    } else {\n" +
"                        inputBuffer = Buffer.from(JSON.stringify(req.body));\n" +
"                    }\n" +
"                }\n" +
"            } else {\n" +
"                // Read from stream\n" +
"                inputBuffer = await new Promise((resolve, reject) => {\n" +
"                    const chunks = [];\n" +
"                    req.on('data', c => chunks.push(c));\n" +
"                    req.on('end', () => resolve(Buffer.concat(chunks)));\n" +
"                    req.on('error', reject);\n" +
"                });\n" +
"            }\n" +
"            env.CONTENT_LENGTH = String(inputBuffer.length);\n" +
"        }\n" +
"\n" +
"        let result;\n" +
"        try {\n" +
"            result = execFileSync(phpCgiBin, ['-c', phpIni, scriptFile], {\n" +
"                env,\n" +
"                cwd: path.join(taskRoot, 'user'),\n" +
"                input: inputBuffer,\n" +
"                maxBuffer: 20 * 1024 * 1024,\n" +
"                timeout: 8000\n" +
"            });\n" +
"        } catch (error) {\n" +
"            if (error.stdout) {\n" +
"                result = error.stdout;\n" +
"            } else {\n" +
"                res.statusCode = 500;\n" +
"                res.setHeader('content-type', 'text/plain');\n" +
"                return res.end('PHP CGI Error: ' + error.message + '\\n' + (error.stderr ? error.stderr.toString() : ''));\n" +
"            }\n" +
"        }\n" +
"\n" +
"        try {\n" +
"            const outStr = result.toString('latin1');\n" +
"            const [headersPart, ...bodyParts] = outStr.split('\\r\\n\\r\\n');\n" +
"            const bodyStr = bodyParts.join('\\r\\n\\r\\n');\n" +
"\n" +
"            const headerLines = headersPart.split('\\r\\n');\n" +
"            for (const line of headerLines) {\n" +
"                const sep = line.indexOf(':');\n" +
"                if (sep > 0) {\n" +
"                    const key = line.substring(0, sep).trim();\n" +
"                    const val = line.substring(sep + 1).trim();\n" +
"                    if (key.toLowerCase() === 'status') {\n" +
"                        res.statusCode = parseInt(val, 10);\n" +
"                    } else {\n" +
"                        res.setHeader(key, val);\n" +
"                    }\n" +
"                }\n" +
"            }\n" +
"            res.end(Buffer.from(bodyStr, 'latin1'));\n" +
"        } catch (error) {\n" +
"            res.statusCode = 500;\n" +
"            res.setHeader('content-type', 'text/plain');\n" +
"            res.end('Wrapper Error: ' + error.message);\n" +
"        }\n" +
"    } catch (err) {\n" +
"        console.error('PHP CGI Error:', err);\n" +
"        res.statusCode = 500;\n" +
"        res.setHeader('content-type', 'text/plain');\n" +
"        res.end('PHP CGI Error: ' + err.message);\n" +
"    }\n" +
"};\n";

    fs.writeFileSync(path.join(funcDir, 'index.js'), indexJsContent);

    // 5. Create .vc-config.json
    const vcConfig = {
        runtime: "nodejs20.x",
        handler: "index.js",
        launcherType: "Nodejs",
        environment: {
            NOW_ENTRYPOINT: "api/index.php",
            NOW_PHP_DEV: "0"
        }
    };
    fs.writeFileSync(path.join(funcDir, '.vc-config.json'), JSON.stringify(vcConfig, null, 2));

    // 6. Create static files directory
    console.log('📦 Copying public files to .vercel/output/static...');
    const staticDir = path.join(workPath, '.vercel/output/static');
    fs.mkdirSync(staticDir, { recursive: true });
    try {
        fs.cpSync(path.join(workPath, 'public'), staticDir, { recursive: true, force: true });
        // Remove PHP files from static so Vercel doesn't serve them as downloads
        const files = fs.readdirSync(staticDir);
        for (const file of files) {
            if (file.endsWith('.php')) {
                fs.unlinkSync(path.join(staticDir, file));
            }
        }
    } catch(e) {
        console.error('Failed to copy public files to static:', e);
    }

    // 7. Create .vercel/output/config.json
    const outputConfig = {
        version: 3,
        routes: [
            { handle: "filesystem" },
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
