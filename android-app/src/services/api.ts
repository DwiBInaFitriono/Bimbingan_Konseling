// URL base API. Gunakan path relatif agar bekerja di Vercel secara otomatis
const API_BASE = import.meta.env.VITE_API_URL || '/api/v1';

// Helper: ambil token sesi yang tersimpan setelah login
function getAuthToken(): string | null {
  return localStorage.getItem('auth_token');
}

function authHeaders(): Record<string, string> {
  const token = getAuthToken();
  return token ? { Authorization: `Bearer ${token}` } : {};
}

// Helper function to handle API responses
async function handleResponse(res: Response) {
  const contentType = res.headers.get('content-type');
  // Sesi habis/tidak valid -> paksa balik ke halaman login
  if (res.status === 401) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('student_data');
  }

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
    const data = await handleResponse(res);
    if (data.success && data.token) {
      localStorage.setItem('auth_token', data.token);
    }
    return data;
  },

  async getJadwal() {
    const res = await fetch(`${API_BASE}/jadwal`, {
      headers: { ...authHeaders() },
    });
    return handleResponse(res);
  },

  async postJadwal(data: { type: string, requested_date: string, requested_time: string, topic: string, description?: string }) {
    const res = await fetch(`${API_BASE}/jadwal`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', ...authHeaders() },
      body: JSON.stringify(data),
    });
    return handleResponse(res);
  },

  async getRiwayat(type: 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi') {
    const res = await fetch(`${API_BASE}/riwayat?type=${type}`, {
      headers: { ...authHeaders() },
    });
    return handleResponse(res);
  },

  async getProfil() {
    const res = await fetch(`${API_BASE}/profil`, {
      headers: { ...authHeaders() },
    });
    return handleResponse(res);
  },

  async updateProfil(data: { email?: string; phone_number?: string; address?: string }) {
    const res = await fetch(`${API_BASE}/profil`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', ...authHeaders() },
      body: JSON.stringify(data),
    });
    return handleResponse(res);
  },

  async ubahPassword(old_password: string, new_password: string) {
    const res = await fetch(`${API_BASE}/profil`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', ...authHeaders() },
      body: JSON.stringify({ old_password, new_password }),
    });
    return handleResponse(res);
  },

  logout() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('student_data');
  }
};
