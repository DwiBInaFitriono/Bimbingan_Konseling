import { db } from './db.js';
import { getAuthenticatedStudentId } from './_authToken';

export default async function handler(req: any, res: any) {
  // Wajib login: ambil student id dari token, JANGAN percaya student_id dari
  // query/body klien (itu yang menyebabkan IDOR — siapapun bisa baca data
  // siswa lain hanya dengan mengganti angka di URL).
  const authenticatedStudentId = getAuthenticatedStudentId(req);
  if (!authenticatedStudentId) {
    return res.status(401).json({ success: false, message: 'Sesi tidak valid. Silakan login kembali.' });
  }

  if (req.method === 'GET') {
    try {
      const query = `
        SELECT cs.*, u.name as counselor_name
        FROM counseling_sessions cs
        LEFT JOIN users u ON cs.guru_bk_id = u.id
        WHERE cs.student_id = ? AND cs.deleted_at IS NULL
        ORDER BY cs.requested_date DESC, cs.requested_time DESC
      `;
      const results = await db.execute(query, [authenticatedStudentId]);
      return res.status(200).json({ success: true, data: results });
    } catch (error: any) {
      console.error('Jadwal GET error:', error);
      return res.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  } else if (req.method === 'POST') {
    try {
      const { type, requested_date, requested_time, topic, description } = req.body;
      if (!type || !requested_date || !requested_time || !topic) {
        return res.status(400).json({ success: false, message: 'Missing required fields: type, requested_date, requested_time, topic' });
      }
      const query = `
        INSERT INTO counseling_sessions (student_id, type, requested_date, requested_time, topic, description, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'menunggu', NOW(), NOW())
      `;
      const result = await db.execute(query, [authenticatedStudentId, type, requested_date, requested_time, topic, description || '']);
      return res.status(200).json({ success: true, message: 'Pengajuan berhasil dikirim', result });
    } catch (error: any) {
      console.error('Jadwal POST error:', error);
      return res.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
