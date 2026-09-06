const API_BASE_URL = import.meta.env.VITE_API_URL || '/api/v1';

function getAuthenticationToken(): string | null {
  return localStorage.getItem('auth_token');
}

function getAuthorizationHeaderMap(): Record<string, string> {
  const activeAuthenticationToken = getAuthenticationToken();
  return activeAuthenticationToken ? { Authorization: `Bearer ${activeAuthenticationToken}` } : {};
}

async function processApiResponsePayload(responseObject: Response) {
  const responseContentType = responseObject.headers.get('content-type');
  if (responseObject.status === 401) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('student_data');
  }

  if (!responseContentType || !responseContentType.includes('application/json')) {
    const errorResponseText = await responseObject.text();
    throw new Error(errorResponseText || 'Server mengembalikan respons non-JSON');
  }
  const parsedResponseJson = await responseObject.json();

  if (!responseObject.ok) {
    throw new Error(parsedResponseJson.message || parsedResponseJson.error || `HTTP error ${responseObject.status}`);
  }
  return parsedResponseJson;
}

export const ApiService = {
  async login(studentIdentificationNumber: string, userPassword: string) {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/auth`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nis: studentIdentificationNumber, password: userPassword }),
      });
      const responseData = await processApiResponsePayload(apiResponse);
      if (responseData.success && responseData.token) {
        localStorage.setItem('auth_token', responseData.token);
      }
      return responseData;
    } catch (authenticationError: any) {
      if (import.meta.env.DEV) {
        const fallbackStudentProfile = {
          id: 1,
          nis: studentIdentificationNumber || '12345',
          name: 'Ahmad Fauzi',
          email: 'ahmad.fauzi@sekolah.sch.id',
          phone_number: '081234567890',
          address: 'Jl. Merdeka No. 12, Bandung',
          gender: 'L',
          school_class_name: 'XII MIPA 1',
          school_class_major: 'MIPA',
          current_point: 95,
          point_deduction: 5,
        };
        const simulatedToken = 'dev_fallback_token_' + Date.now();
        localStorage.setItem('auth_token', simulatedToken);
        return {
          success: true,
          token: simulatedToken,
          student: fallbackStudentProfile,
        };
      }
      throw authenticationError;
    }
  },

  async getJadwal() {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/jadwal`, {
        headers: { ...getAuthorizationHeaderMap() },
      });
      return await processApiResponsePayload(apiResponse);
    } catch (fetchScheduleError: any) {
      if (import.meta.env.DEV) {
        return {
          success: true,
          data: [
            { id: 1, topic: 'Konsultasi Belajar & SNMPTN', status: 'disetujui', requested_date: '2026-09-10', requested_time: '08:00 - 09:00', counselor_name: 'Ibu Ratna, S.Pd' },
            { id: 2, topic: 'Bimbingan Karir Kuliah', status: 'menunggu', requested_date: '2026-09-15', requested_time: '10:00 - 11:00', counselor_name: 'Bpk. Budi Santoso' },
          ],
        };
      }
      throw fetchScheduleError;
    }
  },

  async postJadwal(counselingSchedulePayload: { type: string; requested_date: string; requested_time: string; topic: string; description?: string }) {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/jadwal`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', ...getAuthorizationHeaderMap() },
        body: JSON.stringify(counselingSchedulePayload),
      });
      return await processApiResponsePayload(apiResponse);
    } catch (submitScheduleError: any) {
      if (import.meta.env.DEV) {
        return { success: true, message: 'Pengajuan jadwal berhasil dikirim.' };
      }
      throw submitScheduleError;
    }
  },

  async getRiwayat(historyCategoryType: 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi') {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/riwayat?type=${historyCategoryType}`, {
        headers: { ...getAuthorizationHeaderMap() },
      });
      return await processApiResponsePayload(apiResponse);
    } catch (fetchHistoryError: any) {
      if (import.meta.env.DEV) {
        const dummyHistoryCategoryRecords: Record<string, any[]> = {
          konseling: [
            { id: 1, topic: 'Konsultasi Minat & Bakat', status: 'selesai', requested_date: '2026-08-20', requested_time: '09:00 - 10:00', counselor_name: 'Ibu Ratna, S.Pd' },
            { id: 2, topic: 'Diskusi Rencana Studi Lanjut', status: 'disetujui', requested_date: '2026-09-08', requested_time: '13:00 - 14:00', counselor_name: 'Bpk. Budi Santoso' },
          ],
          pelanggaran: [
            { id: 1, violation_name: 'Terlambat Masuk Sekolah', point_number: 5, date: '2026-08-14', note: 'Terlambat 15 menit' },
          ],
          kasus: [],
          prestasi: [
            { id: 1, achievement_name: 'Juara 1 Lomba Sains Tingkat Kabupaten', achievement_date: '2026-07-28', achievement_level: 'Kabupaten' },
          ],
        };
        return {
          success: true,
          data: dummyHistoryCategoryRecords[historyCategoryType] || [],
        };
      }
      throw fetchHistoryError;
    }
  },

  async getProfil() {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/profil`, {
        headers: { ...getAuthorizationHeaderMap() },
      });
      return await processApiResponsePayload(apiResponse);
    } catch (fetchProfileError: any) {
      if (import.meta.env.DEV) {
        const localStoredProfile = localStorage.getItem('student_data');
        return {
          success: true,
          student: localStoredProfile ? JSON.parse(localStoredProfile) : {
            id: 1,
            nis: '12345',
            name: 'Ahmad Fauzi',
            email: 'ahmad.fauzi@sekolah.sch.id',
            phone_number: '081234567890',
            address: 'Jl. Merdeka No. 12, Bandung',
            school_class_name: 'XII MIPA 1',
            school_class_major: 'MIPA',
            current_point: 95,
            point_deduction: 5,
          },
        };
      }
      throw fetchProfileError;
    }
  },

  async updateProfil(studentProfilePayload: { email?: string; phone_number?: string; address?: string }) {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/profil`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', ...getAuthorizationHeaderMap() },
        body: JSON.stringify(studentProfilePayload),
      });
      return await processApiResponsePayload(apiResponse);
    } catch (updateProfileError: any) {
      if (import.meta.env.DEV) {
        const localStoredProfile = localStorage.getItem('student_data');
        if (localStoredProfile) {
          const updatedProfileData = { ...JSON.parse(localStoredProfile), ...studentProfilePayload };
          localStorage.setItem('student_data', JSON.stringify(updatedProfileData));
        }
        return { success: true, message: 'Profil berhasil diperbarui' };
      }
      throw updateProfileError;
    }
  },

  async ubahPassword(currentPlainPassword: string, updatedPlainPassword: string) {
    try {
      const apiResponse = await fetch(`${API_BASE_URL}/profil`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', ...getAuthorizationHeaderMap() },
        body: JSON.stringify({ old_password: currentPlainPassword, new_password: updatedPlainPassword }),
      });
      return await processApiResponsePayload(apiResponse);
    } catch (changePasswordError: any) {
      if (import.meta.env.DEV) {
        return { success: true, message: 'Kata sandi berhasil diperbarui' };
      }
      throw changePasswordError;
    }
  },

  logout() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('student_data');
  },
};
