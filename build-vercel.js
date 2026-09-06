const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');
const libphp = require('@libphp/almalinux-9-v85');

const originalGetFilesFunction = libphp.getFiles;
libphp.getFiles = async function () {
    const rawFilesMap = await originalGetFilesFunction.call(this);
    const normalizedFilesMap = {};
    for (const [filePathKey, fileEntryValue] of Object.entries(rawFilesMap)) {
        normalizedFilesMap[filePathKey.replace(/\\/g, '/')] = fileEntryValue;
    }
    return normalizedFilesMap;
};

const vercelPhp = require('vercel-php');

async function main() {
    const currentWorkingDirectory = process.cwd();

    const vercelBuilderResult = await vercelPhp.build({
        files: {},
        entrypoint: 'api/index.php',
        workPath: currentWorkingDirectory,
        config: {},
        meta: { isDev: false }
    });

    const targetFunctionDirectory = path.join(currentWorkingDirectory, '.vercel/output/functions/api/index.func');
    fs.mkdirSync(targetFunctionDirectory, { recursive: true });

    const libphpFilesMap = await libphp.getFiles();
    for (const [relativeFilePath, libphpFileObject] of Object.entries(libphpFilesMap)) {
        const destinationFilePath = path.join(targetFunctionDirectory, relativeFilePath);
        fs.mkdirSync(path.dirname(destinationFilePath), { recursive: true });

        const sourceFilePath = typeof libphpFileObject === 'string' ? libphpFileObject : (libphpFileObject.fsPath || null);
        const binaryBufferData = typeof libphpFileObject === 'string' ? null : (libphpFileObject.data || null);

        if (sourceFilePath && fs.existsSync(sourceFilePath)) {
            try {
                const resolvedRealPath = fs.realpathSync(sourceFilePath);
                fs.copyFileSync(resolvedRealPath, destinationFilePath);
            } catch (resolveException) {
                try { fs.copyFileSync(sourceFilePath, destinationFilePath); } catch (copyException) {}
            }
        } else if (binaryBufferData) {
            fs.writeFileSync(destinationFilePath, binaryBufferData);
        }
        try { fs.chmodSync(destinationFilePath, 0o755); } catch (chmodException) {}
    }

    const lambdaFilesMap = vercelBuilderResult.output.files;
    for (const [relativeFilePath, lambdaFileObject] of Object.entries(lambdaFilesMap)) {
        const normalizedFilePath = relativeFilePath.replace(/\\/g, '/');

        if (
            normalizedFilePath.startsWith('user/.git/') ||
            normalizedFilePath.startsWith('user/node_modules/') ||
            normalizedFilePath.startsWith('user/.vercel/')
        ) {
            continue;
        }

        const destinationFilePath = path.join(targetFunctionDirectory, normalizedFilePath);
        fs.mkdirSync(path.dirname(destinationFilePath), { recursive: true });

        if (lambdaFileObject.fsPath) {
            if (fs.existsSync(lambdaFileObject.fsPath)) {
                try {
                    const resolvedRealPath = fs.realpathSync(lambdaFileObject.fsPath);
                    const fileStatistics = fs.statSync(resolvedRealPath);
                    if (fileStatistics.isDirectory()) {
                        fs.cpSync(resolvedRealPath, destinationFilePath, { recursive: true, dereference: true });
                    } else {
                        fs.copyFileSync(resolvedRealPath, destinationFilePath);
                    }
                } catch (resolveException) {
                    try {
                        fs.copyFileSync(lambdaFileObject.fsPath, destinationFilePath);
                    } catch (copyException) {}
                }
            }
        } else if (lambdaFileObject.data) {
            fs.writeFileSync(destinationFilePath, lambdaFileObject.data);
        }
        if (lambdaFileObject.mode) {
            try { fs.chmodSync(destinationFilePath, lambdaFileObject.mode); } catch (chmodException) {}
        }
    }

    const sharedLibraryDirectory = path.join(targetFunctionDirectory, 'lib');
    const phpBinaryDirectory = path.join(targetFunctionDirectory, 'php');
    if (fs.existsSync(sharedLibraryDirectory)) {
        const sharedLibrariesList = fs.readdirSync(sharedLibraryDirectory);
        for (const libraryFileName of sharedLibrariesList) {
            try {
                fs.copyFileSync(path.join(sharedLibraryDirectory, libraryFileName), path.join(phpBinaryDirectory, libraryFileName));
            } catch (copyException) {}
        }
    }

    try {
        const phpCliExecutable = path.join(targetFunctionDirectory, 'php/php');
        const composerCliExecutable = path.join(targetFunctionDirectory, 'php/composer');
        const phpIniConfigurationPath = path.join(targetFunctionDirectory, 'php/php.ini');

        let phpIniConfigurationContent = fs.readFileSync(phpIniConfigurationPath, 'utf8');
        const originalExtensionDirectoryConfig = 'extension_dir=/var/task/php/modules';
        if (phpIniConfigurationContent.includes(originalExtensionDirectoryConfig)) {
            phpIniConfigurationContent = phpIniConfigurationContent.replace(
                originalExtensionDirectoryConfig,
                'extension_dir=' + path.join(targetFunctionDirectory, 'php/modules')
            );
        }
        const buildPhpIniPath = path.join(targetFunctionDirectory, 'php/php-build.ini');
        fs.writeFileSync(buildPhpIniPath, phpIniConfigurationContent);

        if (process.platform !== 'win32') {
            execFileSync(phpCliExecutable, [
                '-c', buildPhpIniPath,
                composerCliExecutable,
                'install',
                '--no-dev',
                '--optimize-autoloader'
            ], {
                cwd: path.join(targetFunctionDirectory, 'user'),
                env: {
                    ...process.env,
                    COMPOSER_HOME: path.join(targetFunctionDirectory, 'composer-home'),
                    PHPRC: buildPhpIniPath,
                    LD_LIBRARY_PATH: path.join(targetFunctionDirectory, 'lib') + (process.env.LD_LIBRARY_PATH ? ':' + process.env.LD_LIBRARY_PATH : '')
                },
                stdio: 'inherit'
            });
        }
    } catch (composerException) {
        console.error('Composer install failed:', composerException);
    }

    const indexJsRunnerContent = "const { execFileSync } = require('child_process');\n" +
"const path = require('path');\n" +
"const fs = require('fs');\n" +
"\n" +
"module.exports = async (incomingHttpRequest, outgoingHttpResponse) => {\n" +
"    try {\n" +
"        const taskRootDirectory = __dirname;\n" +
"        const phpCgiExecutable = path.join(taskRootDirectory, 'php/php-cgi');\n" +
"        const phpIniConfiguration = path.join(taskRootDirectory, 'php/php.ini');\n" +
"        \n" +
"        let executedScriptFilePath = path.join(taskRootDirectory, 'user/api/index.php');\n" +
"        if (!fs.existsSync(executedScriptFilePath)) {\n" +
"            executedScriptFilePath = path.join(taskRootDirectory, 'user/public/index.php');\n" +
"        }\n" +
"\n" +
"        const incomingRequestUrl = incomingHttpRequest.url || '/';\n" +
"        const [extractedRequestPath, extractedQueryString] = incomingRequestUrl.split('?');\n" +
"        const httpRequestMethod = (incomingHttpRequest.method || 'GET').toUpperCase();\n" +
"\n" +
"        if (incomingRequestUrl === '/ls') {\n" +
"            outgoingHttpResponse.statusCode = 200;\n" +
"            outgoingHttpResponse.setHeader('content-type', 'text/plain');\n" +
"            return outgoingHttpResponse.end('NODE JS IS RUNNING\\n');\n" +
"        }\n" +
"\n" +
"        const cgiEnvironmentVariables = {\n" +
"            ...process.env,\n" +
"            GATEWAY_INTERFACE: 'CGI/1.1',\n" +
"            SERVER_PROTOCOL: 'HTTP/1.1',\n" +
"            REQUEST_METHOD: httpRequestMethod,\n" +
"            SCRIPT_FILENAME: executedScriptFilePath,\n" +
"            SCRIPT_NAME: '/index.php',\n" +
"            PATH_INFO: extractedRequestPath || '/',\n" +
"            REQUEST_URI: incomingRequestUrl,\n" +
"            QUERY_STRING: extractedQueryString || '',\n" +
"            HTTP_HOST: incomingHttpRequest.headers.host || incomingHttpRequest.headers.Host || 'localhost',\n" +
"            REDIRECT_STATUS: '200',\n" +
"            LD_LIBRARY_PATH: path.join(taskRootDirectory, 'lib') + ':' + (process.env.LD_LIBRARY_PATH || '')\n" +
"        };\n" +
"\n" +
"        for (const [headerKey, headerValue] of Object.entries(incomingHttpRequest.headers || {})) {\n" +
"            const cgiHeaderKey = 'HTTP_' + headerKey.toUpperCase().replace(/-/g, '_');\n" +
"            cgiEnvironmentVariables[cgiHeaderKey] = headerValue;\n" +
"        }\n" +
"        if (incomingHttpRequest.headers['content-type']) cgiEnvironmentVariables.CONTENT_TYPE = incomingHttpRequest.headers['content-type'];\n" +
"        if (incomingHttpRequest.headers['content-length']) cgiEnvironmentVariables.CONTENT_LENGTH = incomingHttpRequest.headers['content-length'];\n" +
"\n" +
"        let requestBodyBuffer = Buffer.alloc(0);\n" +
"        if (httpRequestMethod !== 'GET' && httpRequestMethod !== 'HEAD') {\n" +
"            if (incomingHttpRequest.body) {\n" +
"                if (Buffer.isBuffer(incomingHttpRequest.body)) {\n" +
"                    requestBodyBuffer = incomingHttpRequest.body;\n" +
"                } else if (typeof incomingHttpRequest.body === 'string') {\n" +
"                    requestBodyBuffer = Buffer.from(incomingHttpRequest.body);\n" +
"                } else {\n" +
"                    const contentTypeHeader = incomingHttpRequest.headers['content-type'] || '';\n" +
"                    if (contentTypeHeader.includes('application/json')) {\n" +
"                        requestBodyBuffer = Buffer.from(JSON.stringify(incomingHttpRequest.body));\n" +
"                    } else if (contentTypeHeader.includes('application/x-www-form-urlencoded')) {\n" +
"                        requestBodyBuffer = Buffer.from(new URLSearchParams(incomingHttpRequest.body).toString());\n" +
"                    } else {\n" +
"                        requestBodyBuffer = Buffer.from(JSON.stringify(incomingHttpRequest.body));\n" +
"                    }\n" +
"                }\n" +
"            } else {\n" +
"                requestBodyBuffer = await new Promise((resolvePromise, rejectPromise) => {\n" +
"                    const streamDataChunks = [];\n" +
"                    incomingHttpRequest.on('data', incomingChunk => streamDataChunks.push(incomingChunk));\n" +
"                    incomingHttpRequest.on('end', () => resolvePromise(Buffer.concat(streamDataChunks)));\n" +
"                    incomingHttpRequest.on('error', rejectPromise);\n" +
"                });\n" +
"            }\n" +
"            cgiEnvironmentVariables.CONTENT_LENGTH = String(requestBodyBuffer.length);\n" +
"        }\n" +
"\n" +
"        let phpExecutionRawOutput;\n" +
"        try {\n" +
"            const { spawnSync } = require('child_process');\n" +
"            const childProcess = spawnSync(phpCgiExecutable, ['-c', phpIniConfiguration, executedScriptFilePath], {\n" +
"                env: cgiEnvironmentVariables,\n" +
"                cwd: path.join(taskRootDirectory, 'user'),\n" +
"                input: requestBodyBuffer,\n" +
"                maxBuffer: 20 * 1024 * 1024\n" +
"            });\n" +
"            if (childProcess.stderr && childProcess.stderr.length > 0) {\n" +
"                console.error('PHP STDERR:', childProcess.stderr.toString());\n" +
"            }\n" +
"            if (childProcess.error) {\n" +
"                throw childProcess.error;\n" +
"            }\n" +
"            if (!childProcess.stdout || childProcess.stdout.length === 0) {\n" +
"                outgoingHttpResponse.statusCode = 500;\n" +
"                outgoingHttpResponse.setHeader('content-type', 'text/plain');\n" +
"                return outgoingHttpResponse.end('PHP CGI returned empty response. Status: ' + childProcess.status + '\\nSTDERR: ' + (childProcess.stderr ? childProcess.stderr.toString() : ''));\n" +
"            }\n" +
"            phpExecutionRawOutput = childProcess.stdout;\n" +
"        } catch (cgiExecutionError) {\n" +
"            outgoingHttpResponse.statusCode = 500;\n" +
"            outgoingHttpResponse.setHeader('content-type', 'text/plain');\n" +
"            return outgoingHttpResponse.end('PHP CGI Execution Error: ' + cgiExecutionError.message);\n" +
"        }\n" +
"\n" +
"        try {\n" +
"            const outputStringLatin1 = phpExecutionRawOutput.toString('latin1');\n" +
"            const [headersSection, ...bodySections] = outputStringLatin1.split('\\r\\n\\r\\n');\n" +
"            const responseBodyString = bodySections.join('\\r\\n\\r\\n');\n" +
"\n" +
"            const headerLinesList = headersSection.split('\\r\\n');\n" +
"            for (const headerLine of headerLinesList) {\n" +
"                const separatorIndex = headerLine.indexOf(':');\n" +
"                if (separatorIndex > 0) {\n" +
"                    const responseHeaderKey = headerLine.substring(0, separatorIndex).trim();\n" +
"                    const responseHeaderValue = headerLine.substring(separatorIndex + 1).trim();\n" +
"                    if (responseHeaderKey.toLowerCase() === 'status') {\n" +
"                        outgoingHttpResponse.statusCode = parseInt(responseHeaderValue, 10);\n" +
"                    } else {\n" +
"                        outgoingHttpResponse.setHeader(responseHeaderKey, responseHeaderValue);\n" +
"                    }\n" +
"                }\n" +
"            }\n" +
"            outgoingHttpResponse.end(Buffer.from(responseBodyString, 'latin1'));\n" +
"        } catch (parsingError) {\n" +
"            outgoingHttpResponse.statusCode = 500;\n" +
"            outgoingHttpResponse.setHeader('content-type', 'text/plain');\n" +
"            outgoingHttpResponse.end('Wrapper Error: ' + parsingError.message);\n" +
"        }\n" +
"    } catch (globalCatchError) {\n" +
"        console.error('PHP CGI Error:', globalCatchError);\n" +
"        outgoingHttpResponse.statusCode = 500;\n" +
"        outgoingHttpResponse.setHeader('content-type', 'text/plain');\n" +
"        outgoingHttpResponse.end('PHP CGI Error: ' + globalCatchError.message);\n" +
"    }\n" +
"};\n";

    fs.writeFileSync(path.join(targetFunctionDirectory, 'index.js'), indexJsRunnerContent);

    const vercelConfigObject = {
        runtime: "nodejs20.x",
        handler: "index.js",
        launcherType: "Nodejs",
        environment: {
            NOW_ENTRYPOINT: "api/index.php",
            NOW_PHP_DEV: "0"
        }
    };
    fs.writeFileSync(path.join(targetFunctionDirectory, '.vc-config.json'), JSON.stringify(vercelConfigObject, null, 2));

    const vercelStaticOutputDirectory = path.join(currentWorkingDirectory, '.vercel/output/static');
    fs.mkdirSync(vercelStaticOutputDirectory, { recursive: true });
    try {
        fs.cpSync(path.join(currentWorkingDirectory, 'public'), vercelStaticOutputDirectory, { recursive: true, force: true });
        const staticDirectoryFiles = fs.readdirSync(vercelStaticOutputDirectory);
        for (const singleFileName of staticDirectoryFiles) {
            if (singleFileName.endsWith('.php')) {
                fs.unlinkSync(path.join(vercelStaticOutputDirectory, singleFileName));
            }
        }
    } catch (staticCopyException) {
        console.error('Failed to copy public files to static:', staticCopyException);
    }

    const vercelOutputConfiguration = {
        version: 3,
        routes: [
            { handle: "filesystem" },
            { src: "/(.*)", dest: "/api/index" }
        ]
    };
    fs.writeFileSync(path.join(currentWorkingDirectory, '.vercel/output/config.json'), JSON.stringify(vercelOutputConfiguration, null, 2));
}

main().catch(fatalExecutionError => {
    console.error('Failed to generate vercel output:', fatalExecutionError);
    process.exit(1);
});
