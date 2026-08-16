import { db } from './db';

export default async function handler(req: any, res: any) {
  if (req.method === 'GET') {
    try {
      const { student_id } = req.query;
      
      if (!student_id) {
        return res.status(400).json({ success: false, message: 'student_id is required' });
      }

      // Query counseling sessions for the student
      const query = `
        SELECT cs.*, u.name as counselor_name
        FROM counseling_sessions cs
        LEFT JOIN users u ON cs.counselor_id = u.id
        WHERE cs.student_id = ?
        ORDER BY cs.schedule_date DESC, cs.schedule_time DESC
      `;
      const results = await db.execute(query, [student_id]);
      
      return res.status(200).json({ success: true, data: results.rows });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
