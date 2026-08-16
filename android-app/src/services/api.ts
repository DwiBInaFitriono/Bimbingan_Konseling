// URL base API. Gunakan path relatif agar bekerja di Vercel secara otomatis
const API_BASE = import.meta.env.VITE_API_URL || '/api/v1';

// Helper function to handle API responses
async function handleResponse(res: Response) {
  const contentType = res.headers.get('content-type');
  
  // Check if response is JSON
  if (!contentType || !contentType.includes('application/json')) {
    const text = await res.text();
    throw new Error(text || 'Server mengembalikan respons non-JSON');
  }
  
  const data = await res.json();
  
  // If response is not OK, throw error with message from server
  if (!res.ok) {
    throw new Error(data.message || data.error || `HTTP error ${res.status}`);
  }
  
  return data;
}

export const ApiService = {
  async login(nis: string, password: string) {
    const res = await fetch(`${API_BASE}/auth`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nis, password }),
    });
    return handleResponse(res);
  },

  async getJadwal(studentId: number) {
    const res = await fetch(`${API_BASE}/jadwal?student_id=${studentId}`);
    return handleResponse(res);
  },

  async postJadwal(data: { student_id: number, type: string, schedule_date: string, schedule_time: string, note: string }) {
    const res = await fetch(`${API_BASE}/jadwal`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    return handleResponse(res);
  },

  async getRiwayat(studentId: number, type: 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi') {
    const res = await fetch(`${API_BASE}/riwayat?student_id=${studentId}&type=${type}`);
    return handleResponse(res);
  }
};
