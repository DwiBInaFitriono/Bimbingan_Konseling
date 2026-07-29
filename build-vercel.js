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

    // 2. Copy lambda files
    console.log('📦 Writing function files to .vercel/output...');
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
                    const stat = fs.lstatSync(fileObj.fsPath);
                    if (stat.isDirectory()) {
                        fs.cpSync(fileObj.fsPath, destPath, { recursive: true, dereference: true });
                    } else if (stat.isSymbolicLink()) {
                        try {
                            fs.cpSync(fileObj.fsPath, destPath, { recursive: true, dereference: true });
                        } catch(e) {
                            // ignore link failure
                        }
                    } else {
                        fs.copyFileSync(fileObj.fsPath, destPath);
                    }
                } catch(e) {
                    try {
                        fs.cpSync(fileObj.fsPath, destPath, { recursive: true, dereference: true });
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

    // 3. Create index.js wrapper to resolve launcher.launcher bug on Node 20/22/24
    console.log('🔧 Creating index.js entrypoint wrapper...');
    const indexJsContent = `const { launcher } = require('./launcher.js');\nmodule.exports = launcher;\n`;
    fs.writeFileSync(path.join(funcDir, 'index.js'), indexJsContent);

    // 4. Create .vc-config.json
    const vcConfig = {
        runtime: "nodejs20.x",
        handler: "index.js",
        launcherType: "Nodejs"
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
