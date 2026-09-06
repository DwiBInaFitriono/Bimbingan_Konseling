import { db } from './db.js';
import bcrypt from 'bcryptjs';
import { getAuthenticatedStudentId } from './_authToken.js';

export default async function handler(incomingRequest: any, serverResponse: any) {
  const authenticatedStudentId = getAuthenticatedStudentId(incomingRequest);
  if (!authenticatedStudentId) {
    return serverResponse.status(401).json({ success: false, message: 'Sesi tidak valid. Silakan login kembali.' });
  }

  if (incomingRequest.method === 'GET') {
    try {
      const studentProfileRows: any[] = await db.execute(
        `SELECT s.*, u.email, u.name, cd.grade, cd.school_class_name, cd.school_class_major
         FROM students s
         JOIN users u ON s.user_id = u.id
         LEFT JOIN classdatas cd ON s.class_id = cd.id
         WHERE s.id = ? AND s.deleted_at IS NULL`,
        [authenticatedStudentId]
      );
      if (!studentProfileRows.length) {
        return serverResponse.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      }
      return serverResponse.status(200).json({ success: true, data: studentProfileRows[0] });
    } catch (profileFetchError: any) {
      return serverResponse.status(500).json({ success: false, error: profileFetchError.message });
    }
  }

  if (incomingRequest.method === 'PUT') {
    try {
      const {
        email: updatedEmail,
        phone_number: updatedPhoneNumber,
        address: updatedAddress,
      } = incomingRequest.body;

      const studentRows: any[] = await db.execute('SELECT user_id FROM students WHERE id = ?', [authenticatedStudentId]);
      if (!studentRows.length) {
        return serverResponse.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      }
      const associatedUserId = studentRows[0].user_id;

      if (updatedEmail) {
        await db.execute('UPDATE users SET email = ?, updated_at = NOW() WHERE id = ?', [updatedEmail, associatedUserId]);
      }
      if (updatedPhoneNumber !== undefined) {
        await db.execute('UPDATE students SET phone_number = ?, updated_at = NOW() WHERE id = ?', [updatedPhoneNumber, authenticatedStudentId]);
      }
      if (updatedAddress !== undefined) {
        await db.execute('UPDATE students SET address = ?, updated_at = NOW() WHERE id = ?', [updatedAddress, authenticatedStudentId]);
      }

      return serverResponse.status(200).json({ success: true, message: 'Profil berhasil diperbarui' });
    } catch (profileUpdateError: any) {
      return serverResponse.status(500).json({ success: false, error: profileUpdateError.message });
    }
  }

  if (incomingRequest.method === 'PATCH') {
    try {
      const { old_password: currentPassword, new_password: newPassword } = incomingRequest.body;
      if (!currentPassword || !newPassword) {
        return serverResponse.status(400).json({ success: false, message: 'old_password dan new_password harus diisi' });
      }
      if (newPassword.length < 6) {
        return serverResponse.status(400).json({ success: false, message: 'Password baru minimal 6 karakter' });
      }

      const studentRows: any[] = await db.execute('SELECT user_id FROM students WHERE id = ?', [authenticatedStudentId]);
      if (!studentRows.length) {
        return serverResponse.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      }
      const associatedUserId = studentRows[0].user_id;

      const userRows: any[] = await db.execute('SELECT password FROM users WHERE id = ?', [associatedUserId]);
      if (!userRows.length) {
        return serverResponse.status(404).json({ success: false, message: 'User tidak ditemukan' });
      }

      const isPasswordValid = typeof userRows[0].password === 'string' && userRows[0].password.startsWith('$2')
        ? await bcrypt.compare(currentPassword, userRows[0].password)
        : (userRows[0].password === currentPassword);

      if (!isPasswordValid) {
        return serverResponse.status(401).json({ success: false, message: 'Password saat ini salah' });
      }

      const hashedNewPassword = await bcrypt.hash(newPassword, 12);
      await db.execute('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?', [hashedNewPassword, associatedUserId]);

      return serverResponse.status(200).json({ success: true, message: 'Password berhasil diubah' });
    } catch (passwordChangeError: any) {
      return serverResponse.status(500).json({ success: false, error: passwordChangeError.message });
    }
  }

  return serverResponse.status(405).json({ message: 'Method Not Allowed' });
}
