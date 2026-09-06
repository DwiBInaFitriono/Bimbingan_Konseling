import { db } from './db.js';
import { getAuthenticatedStudentId } from './_authToken.js';

export default async function handler(incomingRequest: any, serverResponse: any) {
  const authenticatedStudentId = getAuthenticatedStudentId(incomingRequest);
  if (!authenticatedStudentId) {
    return serverResponse.status(401).json({ success: false, message: 'Sesi tidak valid. Silakan login kembali.' });
  }

  if (incomingRequest.method === 'GET') {
    try {
      const selectScheduleQuery = `
        SELECT cs.*, u.name as counselor_name
        FROM counseling_sessions cs
        LEFT JOIN users u ON cs.guru_bk_id = u.id
        WHERE cs.student_id = ? AND cs.deleted_at IS NULL
        ORDER BY cs.requested_date DESC, cs.requested_time DESC
      `;
      const scheduleRecords = await db.execute(selectScheduleQuery, [authenticatedStudentId]);
      return serverResponse.status(200).json({ success: true, data: scheduleRecords });
    } catch (scheduleFetchError: any) {
      console.error('Jadwal GET error:', scheduleFetchError);
      return serverResponse.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  } else if (incomingRequest.method === 'POST') {
    try {
      const {
        type: counselingType,
        requested_date: requestedCounselingDate,
        requested_time: requestedCounselingTime,
        topic: counselingTopic,
        description: counselingDescription,
      } = incomingRequest.body;

      if (!counselingType || !requestedCounselingDate || !requestedCounselingTime || !counselingTopic) {
        return serverResponse.status(400).json({
          success: false,
          message: 'Missing required fields: type, requested_date, requested_time, topic',
        });
      }

      const insertScheduleQuery = `
        INSERT INTO counseling_sessions (student_id, type, requested_date, requested_time, topic, description, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'menunggu', NOW(), NOW())
      `;
      const scheduleInsertResult = await db.execute(insertScheduleQuery, [
        authenticatedStudentId,
        counselingType,
        requestedCounselingDate,
        requestedCounselingTime,
        counselingTopic,
        counselingDescription || '',
      ]);
      return serverResponse.status(200).json({ success: true, message: 'Pengajuan berhasil dikirim', result: scheduleInsertResult });
    } catch (scheduleSubmitError: any) {
      console.error('Jadwal POST error:', scheduleSubmitError);
      return serverResponse.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
    }
  }
  return serverResponse.status(405).json({ message: 'Method Not Allowed' });
}
