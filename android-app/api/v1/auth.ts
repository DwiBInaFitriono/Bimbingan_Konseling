import { db } from './db.js';
import bcrypt from 'bcryptjs';
import { signToken } from './_authToken';
import { checkRateLimit, getClientIp } from './_rateLimit';
export default async function handler(req: any, res: any) {
  if (req.method === 'POST') {
    try {
      // Batasi percobaan login: maksimal 10 percobaan per IP per 5 menit,
      // untuk memperlambat brute-force pada instance yang sedang aktif.
      const ip = getClientIp(req);
      if (!checkRateLimit(`login:${ip}`, 10, 5 * 60 * 1000)) {
        return res.status(429).json({ success: false, message: 'Terlalu banyak percobaan login. Coba lagi beberapa menit.' });
      }

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
          // Verifikasi password menggunakan bcrypt. Hash yang dibuat oleh
          // Laravel (Hash::make, prefix $2y$) tetap kompatibel dengan bcryptjs.
          const isPasswordValid = typeof user.password === 'string' && user.password.startsWith('$2')
            ? await bcrypt.compare(password, user.password)
            : false;

          if (isPasswordValid) {
            const token = signToken(student.id);
            return res.status(200).json({
              success: true,
              message: 'Login berhasil',
              token,
              student: student,
              user: { id: user.id, name: user.name, email: user.email, role: user.role }
            });
          }
        }
      }

      return res.status(401).json({ success: false, message: 'NIS atau Password salah' });
    } catch (error: any) {
      console.error('Login error:', error);
      return res.status(500).json({ success: false, message: 'Terjadi kesalahan server: ' + (error.message || error.toString()), error: error.stack });
    }
  }
  return res.status(405).json({ success: false, message: 'Method Not Allowed' });
}
