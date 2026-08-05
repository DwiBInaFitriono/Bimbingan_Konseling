const fs = require('fs');
const path = require('path');

const controllersDir = path.join(__dirname, 'app', 'Http', 'Controllers');
const guruDir = path.join(controllersDir, 'Guru_BK');
const siswaDir = path.join(controllersDir, 'Siswa');

if (!fs.existsSync(guruDir)) fs.mkdirSync(guruDir);
if (!fs.existsSync(siswaDir)) fs.mkdirSync(siswaDir);

const filesToMove = [
    'Achievements.php',
    'Admin.php',
    'CaseReport.php',
    'CounselingSessionController.php',
    'DataClass.php',
    'DataPoint.php',
    'ParentStudent.php',
    'PointCategory.php'
];

for (const file of filesToMove) {
    const srcPath = path.join(controllersDir, file);
    const destPath = path.join(guruDir, file);
    
    if (fs.existsSync(srcPath)) {
        let content = fs.readFileSync(srcPath, 'utf8');
        // Update namespace
        content = content.replace('namespace App\\Http\\Controllers;', 'namespace App\\Http\\Controllers\\Guru_BK;');
        fs.writeFileSync(destPath, content);
        fs.unlinkSync(srcPath);
        console.log(`Moved and updated namespace for ${file} to Guru_BK`);
    }
}
