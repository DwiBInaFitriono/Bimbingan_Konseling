// URL base API. Gunakan path relatif agar bekerja di Vercel secara otomatis
const API_BASE = import.meta.env.VITE_API_URL || '/api/v1';

// Helper function to handle API responses
async function handleResponse(res: Response) {
  const contentType = res.headers.get('content-type');
  if (!contentType || !contentType.includes('application/json')) {
    const text = await res.text();
    throw new Error(text || 'Server mengembalikan respons non-JSON');
  }
  const data = await res.json();
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

  async getJadwal(studentId: string | number) {
    const res = await fetch(`${API_BASE}/jadwal?student_id=${studentId}`);
    return handleResponse(res);
  },

  async postJadwal(data: { student_id: string | number, type: string, requested_date: string, requested_time: string, topic: string, description?: string }) {
    const res = await fetch(`${API_BASE}/jadwal`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    return handleResponse(res);
  },

  async getRiwayat(studentId: string | number, type: 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi') {
    const res = await fetch(`${API_BASE}/riwayat?student_id=${studentId}&type=${type}`);
    return handleResponse(res);
  },

  async getProfil(studentId: string | number) {
    const res = await fetch(`${API_BASE}/profil?student_id=${studentId}`);
    return handleResponse(res);
  },

  async updateProfil(studentId: string | number, data: { email?: string; phone_number?: string; address?: string }) {
    const res = await fetch(`${API_BASE}/profil`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ student_id: studentId, ...data }),
    });
    return handleResponse(res);
  },

  async ubahPassword(studentId: string | number, old_password: string, new_password: string) {
    const res = await fetch(`${API_BASE}/profil`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ student_id: studentId, old_password, new_password }),
    });
    return handleResponse(res);
  },
};
