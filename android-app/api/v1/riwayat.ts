import { db } from './db';
import { getAuthenticatedStudentId } from './_authToken';

export default async function handler(req: any, res: any) {
  // Wajib login: student_id diambil dari token yang sudah diverifikasi,
  // bukan dari query string klien (mencegah IDOR — baca riwayat siswa lain).
  const authenticatedStudentId = getAuthenticatedStudentId(req);
  if (!authenticatedStudentId) {
    return res.status(401).json({ success: false, message: 'Sesi tidak valid. Silakan login kembali.' });
  }

  if (req.method === 'GET') {
    try {
      const { type } = req.query;

      if (!type) {
        return res.status(400).json({ success: false, message: 'type is required' });
      }

      let query = '';
      let results;

      switch(type) {
        case 'konseling':
          query = `
            SELECT * FROM counseling_sessions
            WHERE student_id = ? AND status = 'completed'
            ORDER BY schedule_date DESC
          `;
          break;
        case 'pelanggaran':
          query = `
            SELECT pd.*, pc.name as category_name, pc.point as category_point
            FROM point_datas pd
            LEFT JOIN data_point_categories pc ON pd.point_category_id = pc.id
            WHERE pd.student_id = ?
            ORDER BY pd.created_at DESC
          `;
          break;
        case 'kasus':
          query = `
            SELECT * FROM case_studies
            WHERE student_id = ?
            ORDER BY created_at DESC
          `;
          break;
        case 'prestasi':
          query = `
            SELECT * FROM achievements
            WHERE student_id = ?
            ORDER BY created_at DESC
          `;
          break;
        default:
          return res.status(400).json({ success: false, message: 'Invalid type' });
      }

      results = await db.execute(query, [authenticatedStudentId]);
      return res.status(200).json({ success: true, data: results });

    } catch (error: any) {
      console.error('Riwayat GET error:', error);
      return res.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
