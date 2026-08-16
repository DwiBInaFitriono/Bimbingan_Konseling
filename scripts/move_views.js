const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, 'resources', 'views');
const guruDir = path.join(viewsDir, 'Guru_BK');
const siswaDir = path.join(viewsDir, 'Siswa');

if (!fs.existsSync(guruDir)) fs.mkdirSync(guruDir);
if (!fs.existsSync(siswaDir)) fs.mkdirSync(siswaDir);
if (!fs.existsSync(path.join(siswaDir, 'counseling'))) fs.mkdirSync(path.join(siswaDir, 'counseling'));
if (!fs.existsSync(path.join(siswaDir, 'Student'))) fs.mkdirSync(path.join(siswaDir, 'Student'));

const guruFolders = ['Achievement', 'CaseStudy', 'Class', 'Parents', 'Point', 'PointCategory', 'Student', 'counseling'];

for (const folder of guruFolders) {
    const srcPath = path.join(viewsDir, folder);
    const destPath = path.join(guruDir, folder);
    
    if (fs.existsSync(srcPath)) {
        fs.renameSync(srcPath, destPath);
        console.log(`Moved ${folder} to Guru_BK`);
    }
}

const siswaIndex = path.join(guruDir, 'counseling', 'siswa_index.blade.php');
if (fs.existsSync(siswaIndex)) {
    fs.renameSync(siswaIndex, path.join(siswaDir, 'counseling', 'siswa_index.blade.php'));
    console.log('Moved siswa_index.blade.php to Siswa/counseling');
}

const cetakSp = path.join(guruDir, 'Student', 'cetak_sp.blade.php');
if (fs.existsSync(cetakSp)) {
    fs.renameSync(cetakSp, path.join(siswaDir, 'Student', 'cetak_sp.blade.php'));
    console.log('Moved cetak_sp.blade.php to Siswa/Student');
}
