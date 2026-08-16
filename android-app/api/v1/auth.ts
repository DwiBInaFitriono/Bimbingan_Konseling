import { db } from './db.js';

export default async function handler(req: any, res: any) {
  if (req.method === 'POST') {
    try {
      const { nis, password } = req.body;
      
      if (!nis || !password) {
        return res.status(400).json({ success: false, message: 'NIS dan password harus diisi' });
      }
      
      // First, check the student by NIS
      const studentResult = await db.execute('SELECT * FROM students WHERE nis = ?', [nis]);
      
      if (studentResult && studentResult.length > 0) {
        const student: any = studentResult[0];
        
        // Find the associated user account to verify password
        const userResult = await db.execute('SELECT * FROM users WHERE id = ?', [student.user_id]);
        
        if (userResult && userResult.length > 0) {
          const user: any = userResult[0];
          
          // Verify password - compare with plain text for now
          // TODO: In production, use bcrypt.compare(password, user.password)
          if (user.password === password) {
            return res.status(200).json({
              success: true,
              message: 'Login berhasil',
              student: student,
              user: { id: user.id, name: user.name, email: user.email, role: user.role }
            });
          }
        }
      }
      
      return res.status(401).json({ success: false, message: 'NIS atau Password salah' });
    } catch (error: any) {
      console.error('Login error:', error);
      return res.status(500).json({ success: false, message: 'Terjadi kesalahan server', error: error.message });
    }
  }
  return res.status(405).json({ success: false, message: 'Method Not Allowed' });
}
