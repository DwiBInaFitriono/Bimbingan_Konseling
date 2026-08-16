import { db } from './db.js';
import bcrypt from 'bcryptjs';

export default async function handler(req: any, res: any) {
  // ─── GET: ambil data profil student ──────────────────────────────────────────
  if (req.method === 'GET') {
    try {
      const { student_id } = req.query;
      if (!student_id) return res.status(400).json({ success: false, message: 'student_id required' });

      const rows: any[] = await db.execute(
        `SELECT s.*, u.email, u.name, cd.grade, cd.school_class_name, cd.school_class_major
         FROM students s
         JOIN users u ON s.user_id = u.id
         LEFT JOIN classdatas cd ON s.class_id = cd.id
         WHERE s.id = ? AND s.deleted_at IS NULL`,
        [student_id]
      );
      if (!rows.length) return res.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      return res.status(200).json({ success: true, data: rows[0] });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }

  // ─── PUT: update email/phone profil ──────────────────────────────────────────
  if (req.method === 'PUT') {
    try {
      const { student_id, email, phone_number, address } = req.body;
      if (!student_id) return res.status(400).json({ success: false, message: 'student_id required' });

      const studentRows: any[] = await db.execute('SELECT user_id FROM students WHERE id = ?', [student_id]);
      if (!studentRows.length) return res.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      const userId = studentRows[0].user_id;

      if (email) {
        await db.execute('UPDATE users SET email = ?, updated_at = NOW() WHERE id = ?', [email, userId]);
      }
      if (phone_number !== undefined) {
        await db.execute('UPDATE students SET phone_number = ?, updated_at = NOW() WHERE id = ?', [phone_number, student_id]);
      }
      if (address !== undefined) {
        await db.execute('UPDATE students SET address = ?, updated_at = NOW() WHERE id = ?', [address, student_id]);
      }

      return res.status(200).json({ success: true, message: 'Profil berhasil diperbarui' });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }

  // ─── PATCH: ubah password ─────────────────────────────────────────────────────
  if (req.method === 'PATCH') {
    try {
      const { student_id, old_password, new_password } = req.body;
      if (!student_id || !old_password || !new_password) {
        return res.status(400).json({ success: false, message: 'student_id, old_password, dan new_password harus diisi' });
      }
      if (new_password.length < 6) {
        return res.status(400).json({ success: false, message: 'Password baru minimal 6 karakter' });
      }

      const studentRows: any[] = await db.execute('SELECT user_id FROM students WHERE id = ?', [student_id]);
      if (!studentRows.length) return res.status(404).json({ success: false, message: 'Siswa tidak ditemukan' });
      const userId = studentRows[0].user_id;

      const userRows: any[] = await db.execute('SELECT password FROM users WHERE id = ?', [userId]);
      if (!userRows.length) return res.status(404).json({ success: false, message: 'User tidak ditemukan' });

      const isValid = await bcrypt.compare(old_password, userRows[0].password);
      if (!isValid) {
        return res.status(401).json({ success: false, message: 'Password saat ini salah' });
      }

      const hashed = await bcrypt.hash(new_password, 12);
      await db.execute('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?', [hashed, userId]);

      return res.status(200).json({ success: true, message: 'Password berhasil diubah' });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }

  return res.status(405).json({ message: 'Method Not Allowed' });
}
