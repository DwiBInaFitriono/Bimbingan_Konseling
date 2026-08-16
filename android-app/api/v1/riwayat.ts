import { db } from './db.js';

export default async function handler(req: any, res: any) {
  if (req.method === 'GET') {
    try {
      const { student_id, type } = req.query;
      if (!student_id || !type) {
        return res.status(400).json({ success: false, message: 'student_id and type are required' });
      }

      let query = '';

      switch(type) {
        case 'konseling':
          query = `
            SELECT cs.*, u.name as counselor_name
            FROM counseling_sessions cs
            LEFT JOIN users u ON cs.guru_bk_id = u.id
            WHERE cs.student_id = ? AND cs.deleted_at IS NULL
            ORDER BY cs.requested_date DESC
          `;
          break;
        case 'pelanggaran':
          query = `
            SELECT pd.*, dpc.category_of_violation as category_name, pd.point_number as category_point
            FROM point_datas pd
            LEFT JOIN data_point_categories dpc ON (pd.point_number >= dpc.category_score_min AND pd.point_number <= dpc.category_score_max)
            WHERE pd.student_id = ? AND pd.deleted_at IS NULL
            ORDER BY pd.violation_date DESC
          `;
          break;
        case 'kasus':
          query = `
            SELECT * FROM case_studies 
            WHERE student_id = ? AND deleted_at IS NULL
            ORDER BY created_at DESC
          `;
          break;
        case 'prestasi':
          query = `
            SELECT * FROM achievements 
            WHERE student_id = ? AND deleted_at IS NULL
            ORDER BY achievement_date DESC
          `;
          break;
        default:
          return res.status(400).json({ success: false, message: 'Invalid type' });
      }

      const results = await db.execute(query, [student_id]);
      return res.status(200).json({ success: true, data: results });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
