// URL base API. Jika di lokal dengan Vercel, biasanya http://localhost:3000
// Saat sudah di-deploy, ganti dengan domain Vercel Anda (misal: https://your-laravel-vercel-app.vercel.app)
const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:3000/api/v1';

export const ApiService = {
  async login(nis: string, password: string) {
    const res = await fetch(`${API_BASE}/auth`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nis, password }),
    });
    return res.json();
  },

  async getJadwal(studentId: number) {
    const res = await fetch(`${API_BASE}/jadwal?student_id=${studentId}`);
    return res.json();
  },

  async postJadwal(data: { student_id: number, type: string, schedule_date: string, schedule_time: string, note: string }) {
    const res = await fetch(`${API_BASE}/jadwal`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    return res.json();
  },

  async getRiwayat(studentId: number, type: 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi') {
    const res = await fetch(`${API_BASE}/riwayat?student_id=${studentId}&type=${type}`);
    return res.json();
  }
};
