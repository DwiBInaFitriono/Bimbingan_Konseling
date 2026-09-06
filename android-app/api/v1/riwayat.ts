import { db } from './db.js';
import { getAuthenticatedStudentId } from './_authToken.js';

export default async function handler(incomingRequest: any, serverResponse: any) {
  const authenticatedStudentId = getAuthenticatedStudentId(incomingRequest);
  if (!authenticatedStudentId) {
    return serverResponse.status(401).json({ success: false, message: 'Sesi tidak valid. Silakan login kembali.' });
  }

  if (incomingRequest.method === 'GET') {
    try {
      const { type: historyCategoryType } = incomingRequest.query;

      if (!historyCategoryType) {
        return serverResponse.status(400).json({ success: false, message: 'type is required' });
      }

      let historySqlQuery = '';

      switch (historyCategoryType) {
        case 'konseling':
          historySqlQuery = `
            SELECT cs.*, u.name as counselor_name
            FROM counseling_sessions cs
            LEFT JOIN users u ON cs.guru_bk_id = u.id
            WHERE cs.student_id = ? AND cs.deleted_at IS NULL
            ORDER BY cs.requested_date DESC
          `;
          break;
        case 'pelanggaran':
          historySqlQuery = `
            SELECT pd.*, dpc.category_of_violation as category_name, pd.point_number as category_point
            FROM point_datas pd
            LEFT JOIN data_point_categories dpc ON (pd.point_number >= dpc.category_score_min AND pd.point_number <= dpc.category_score_max)
            WHERE pd.student_id = ? AND pd.deleted_at IS NULL
            ORDER BY pd.violation_date DESC
          `;
          break;
        case 'kasus':
          historySqlQuery = `
            SELECT * FROM case_studies 
            WHERE student_id = ? AND deleted_at IS NULL
            ORDER BY created_at DESC
          `;
          break;
        case 'prestasi':
          historySqlQuery = `
            SELECT * FROM achievements 
            WHERE student_id = ? AND deleted_at IS NULL
            ORDER BY achievement_date DESC
          `;
          break;
        default:
          return serverResponse.status(400).json({ success: false, message: 'Invalid type' });
      }

      const historyRecords = await db.execute(historySqlQuery, [authenticatedStudentId]);
      return serverResponse.status(200).json({ success: true, data: historyRecords });
    } catch (historyFetchError: any) {
      console.error('Riwayat GET error:', historyFetchError);
      return serverResponse.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  }
  return serverResponse.status(405).json({ message: 'Method Not Allowed' });
}
