const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, 'resources', 'views');
const styleCssPath = path.join(__dirname, 'public', 'assets', 'css', 'style.css');
const scriptJsPath = path.join(__dirname, 'public', 'assets', 'js', 'script.js');

let allCss = '\n/* Extracted from Blade files */\n';
let allJs = '\n/* Extracted from Blade files */\ndocument.addEventListener("DOMContentLoaded", () => {\n';

const blacklist = ['welcome.blade.php', 'login.blade.php', 'register.blade.php'];

function traverseDir(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            traverseDir(fullPath);
        } else if (fullPath.endsWith('.blade.php')) {
            if (blacklist.includes(file)) {
                console.log('Skipping extraction for blacklisted file: ' + file);
                continue;
            }
            if (file.includes('cetak') || file.includes('pdf')) {
                console.log('Skipping extraction for standalone print file: ' + file);
                continue;
            }
            processFile(fullPath);
        }
    }
}

function processFile(filePath) {
    let content = fs.readFileSync(filePath, 'utf8');
    let originalContent = content;

    const styleRegex = /<style[^>]*>([\s\S]*?)<\/style>/gi;
    let match;
    while ((match = styleRegex.exec(content)) !== null) {
        allCss += match[1] + '\n';
    }
    content = content.replace(styleRegex, '');

    const scriptRegex = /<script(?![^>]*src=)[^>]*>([\s\S]*?)<\/script>/gi;
    while ((match = scriptRegex.exec(content)) !== null) {
        let scriptContent = match[1].trim();
        if (scriptContent.length > 0) {
            allJs += `    try {\n        ${scriptContent}\n    } catch(e) { console.warn('Extracted script warning:', e); }\n`;
        }
    }
    content = content.replace(scriptRegex, '');

    if (content !== originalContent) {
        fs.writeFileSync(filePath, content);
        console.log('Extracted from: ' + filePath);
    }
}

fs.writeFileSync(scriptJsPath, '');

traverseDir(viewsDir);

allJs += '});\n';

fs.appendFileSync(styleCssPath, allCss);
fs.appendFileSync(scriptJsPath, allJs);

console.log('Extraction complete.');
