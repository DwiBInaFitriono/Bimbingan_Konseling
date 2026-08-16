import { db } from './db.js';

export default async function handler(req: any, res: any) {
  if (req.method === 'GET') {
    try {
      const { student_id } = req.query;
      if (!student_id) {
        return res.status(400).json({ success: false, message: 'student_id is required' });
      }
      const query = `
        SELECT cs.*, u.name as counselor_name
        FROM counseling_sessions cs
        LEFT JOIN users u ON cs.guru_bk_id = u.id
        WHERE cs.student_id = ? AND cs.deleted_at IS NULL
        ORDER BY cs.requested_date DESC, cs.requested_time DESC
      `;
      const results = await db.execute(query, [student_id]);
      return res.status(200).json({ success: true, data: results });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  } else if (req.method === 'POST') {
    try {
      const { student_id, type, requested_date, requested_time, topic, description } = req.body;
      if (!student_id || !type || !requested_date || !requested_time || !topic) {
        return res.status(400).json({ success: false, message: 'Missing required fields: student_id, type, requested_date, requested_time, topic' });
      }
      const query = `
        INSERT INTO counseling_sessions (student_id, type, requested_date, requested_time, topic, description, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
      `;
      const result = await db.execute(query, [student_id, type, requested_date, requested_time, topic, description || '']);
      return res.status(200).json({ success: true, message: 'Pengajuan berhasil dikirim', result });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
