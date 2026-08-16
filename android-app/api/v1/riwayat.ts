import { db } from './db';

export default async function handler(req: any, res: any) {
  if (req.method === 'GET') {
    try {
      const { student_id, type } = req.query;
      
      if (!student_id || !type) {
        return res.status(400).json({ success: false, message: 'student_id and type are required' });
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
      
      results = await db.execute(query, [student_id]);
      return res.status(200).json({ success: true, data: results });
      
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
