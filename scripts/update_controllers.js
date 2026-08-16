const fs = require('fs');
const path = require('path');

const guruControllersDir = path.join(__dirname, 'app', 'Http', 'Controllers', 'Guru_BK');
const viewFolders = ['Achievement', 'CaseStudy', 'Class', 'Parents', 'Point', 'PointCategory', 'Student', 'counseling'];

const files = fs.readdirSync(guruControllersDir);

for (const file of files) {
    if (file.endsWith('.php')) {
        const filePath = path.join(guruControllersDir, file);
        let content = fs.readFileSync(filePath, 'utf8');
        let originalContent = content;

        for (const folder of viewFolders) {
            // Regex to find view('Folder.file') or view('Folder.file', ...)
            const viewRegex = new RegExp(`view\\((['"])${folder}\\.`, 'g');
            content = content.replace(viewRegex, `view($1Guru_BK.${folder}.`);
        }

        if (content !== originalContent) {
            fs.writeFileSync(filePath, content);
            console.log(`Updated view paths in ${file}`);
        }
    }
}
