import { db } from './db.js';
import bcrypt from 'bcryptjs';
import { signToken } from './_authToken.js';
import { checkRateLimit, getClientIp } from './_rateLimit.js';

export default async function handler(incomingRequest: any, serverResponse: any) {
  if (incomingRequest.method === 'POST') {
    try {
      const clientIpAddress = getClientIp(incomingRequest);
      if (!checkRateLimit(`login:${clientIpAddress}`, 10, 5 * 60 * 1000)) {
        return serverResponse.status(429).json({ success: false, message: 'Terlalu banyak percobaan login. Coba lagi beberapa menit.' });
      }

      const { nis: studentIdentificationNumber, password: userPlainPassword } = incomingRequest.body;

      if (!studentIdentificationNumber || !userPlainPassword) {
        return serverResponse.status(400).json({ success: false, message: 'NIS dan password harus diisi' });
      }

      const studentQueryResult = await db.execute('SELECT * FROM students WHERE nis = ?', [studentIdentificationNumber]);

      if (studentQueryResult && studentQueryResult.length > 0) {
        const matchingStudent: any = studentQueryResult[0];

        const userQueryResult = await db.execute('SELECT * FROM users WHERE id = ?', [matchingStudent.user_id]);

        if (userQueryResult && userQueryResult.length > 0) {
          const matchingUser: any = userQueryResult[0];
          const isPasswordValid = typeof matchingUser.password === 'string' && matchingUser.password.startsWith('$2')
            ? await bcrypt.compare(userPlainPassword, matchingUser.password)
            : (matchingUser.password === userPlainPassword);

          if (isPasswordValid) {
            const generatedAuthToken = signToken(matchingStudent.id);
            return serverResponse.status(200).json({
              success: true,
              message: 'Login berhasil',
              token: generatedAuthToken,
              student: {
                ...matchingStudent,
                name: matchingStudent.nama || matchingStudent.nama_lengkap || matchingStudent.name || matchingUser.name,
              },
              user: { id: matchingUser.id, name: matchingUser.name, email: matchingUser.email, role: matchingUser.role },
            });
          }
        }
      }

      return serverResponse.status(401).json({ success: false, message: 'NIS atau Password salah' });
    } catch (authenticationError: any) {
      console.error('Login error:', authenticationError);
      return serverResponse.status(500).json({ success: false, message: 'Terjadi kesalahan server: ' + (authenticationError.message || authenticationError.toString()), error: authenticationError.stack });
    }
  }
  return serverResponse.status(405).json({ success: false, message: 'Method Not Allowed' });
}
