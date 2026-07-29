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

    // 4. Create index.js wrapper using synchronous PHP-CGI (req, res) handler
    console.log('🔧 Creating index.js entrypoint wrapper with (req, res) PHP-CGI runner...');
    const indexJsContent = "const { execFileSync } = require('child_process');\n" +
"const path = require('path');\n" +
"const fs = require('fs');\n" +
"\n" +
"module.exports = (req, res) => {\n" +
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
"        if (reqUrl === '/health') {\n" +
"            res.statusCode = 200;\n" +
"            res.setHeader('content-type', 'text/plain');\n" +
"            return res.end('NODE JS IS RUNNING');\n" +
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
"\n" +
"        let inputBuffer = Buffer.alloc(0);\n" +
"        if (req.body) {\n" +
"            inputBuffer = typeof req.body === 'string' ? Buffer.from(req.body) : req.body;\n" +
"            env.CONTENT_LENGTH = String(inputBuffer.length);\n" +
"            if (req.headers['content-type']) env.CONTENT_TYPE = req.headers['content-type'];\n" +
"        }\n" +
"\n" +
"        const rawOutput = execFileSync(phpCgiBin, ['-c', phpIni], {\n" +
"            env,\n" +
"            cwd: path.join(taskRoot, 'user'),\n" +
"            input: inputBuffer,\n" +
"            maxBuffer: 20 * 1024 * 1024,\n" +
"            timeout: 8000\n" +
"        });\n" +
"\n" +
"        let headerEndIndex = rawOutput.indexOf('\\r\\n\\r\\n');\n" +
"        let headerLen = 4;\n" +
"        if (headerEndIndex === -1) {\n" +
"            headerEndIndex = rawOutput.indexOf('\\n\\n');\n" +
"            headerLen = 2;\n" +
"        }\n" +
"\n" +
"        if (headerEndIndex === -1) {\n" +
"            res.statusCode = 200;\n" +
"            res.setHeader('content-type', 'text/html; charset=UTF-8');\n" +
"            return res.end(rawOutput);\n" +
"        }\n" +
"\n" +
"        const headerPart = rawOutput.slice(0, headerEndIndex).toString('utf8');\n" +
"        const bodyPart = rawOutput.slice(headerEndIndex + headerLen);\n" +
"\n" +
"        headerPart.split(/\\r?\\n/).forEach(line => {\n" +
"            const colonIndex = line.indexOf(':');\n" +
"            if (colonIndex !== -1) {\n" +
"                const key = line.slice(0, colonIndex).trim().toLowerCase();\n" +
"                const val = line.slice(colonIndex + 1).trim();\n" +
"                if (key === 'status') {\n" +
"                    res.statusCode = parseInt(val, 10) || 200;\n" +
"                } else {\n" +
"                    res.setHeader(key, val);\n" +
"                }\n" +
"            }\n" +
"        });\n" +
"\n" +
"        res.end(bodyPart);\n" +
"    } catch (err) {\n" +
"        console.error('PHP CGI Error:', err);\n" +
"        res.statusCode = 500;\n" +
"        res.setHeader('content-type', 'text/plain');\n" +
"        res.end('PHP CGI Error: ' + (err.stderr ? err.stderr.toString() : err.message));\n" +
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

    // 6. Create .vercel/output/config.json
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
