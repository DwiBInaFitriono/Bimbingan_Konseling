const fs = require('fs');
const path = require('path');

const guruControllersDir = path.join(__dirname, 'app', 'Http', 'Controllers', 'Guru_BK');
const files = fs.readdirSync(guruControllersDir);

for (const file of files) {
    if (file.endsWith('.php')) {
        const filePath = path.join(guruControllersDir, file);
        let content = fs.readFileSync(filePath, 'utf8');
        
        if (!content.includes('use App\\Http\\Controllers\\Controller;')) {
            content = content.replace(
                'namespace App\\Http\\Controllers\\Guru_BK;', 
                'namespace App\\Http\\Controllers\\Guru_BK;\n\nuse App\\Http\\Controllers\\Controller;'
            );
            fs.writeFileSync(filePath, content);
            console.log(`Added Controller import to ${file}`);
        }
    }
}
