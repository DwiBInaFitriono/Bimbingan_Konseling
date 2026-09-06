import React, { useState, useEffect } from 'react';
import { Screen } from '../types';
import { COLOR_PRIMARY, COLOR_VIOLET } from '../constants';
import { FadeUpAnimation } from '../components/Animations';
import { SubHeader } from '../components/SubHeader';
import { InputField } from '../components/InputField';
import { ApiService } from '../services/api';

export function EditProfilScreen({ navigate }: { navigate: (targetScreen: Screen) => void }) {
  const [studentProfile, setStudentProfile] = useState<any>(null);
  const [emailAddress, setEmailAddress] = useState('');
  const [telephoneNumber, setTelephoneNumber] = useState('');
  const [residentialAddress, setResidentialAddress] = useState('');
  const [isSavingProfile, setIsSavingProfile] = useState(false);
  const [feedbackToast, setFeedbackToast] = useState<{ messageText: string; isSuccess: boolean } | null>(null);

  const displayFeedbackToast = (messageText: string, isSuccess = false) => {
    setFeedbackToast({ messageText, isSuccess });
    setTimeout(() => setFeedbackToast(null), 3500);
  };

  useEffect(() => {
    const studentStorageData = localStorage.getItem('student_data');
    if (studentStorageData) {
      const parsedStudent = JSON.parse(studentStorageData);
      setStudentProfile(parsedStudent);
      setEmailAddress(parsedStudent.email || '');
      setTelephoneNumber(parsedStudent.phone_number || '');
      setResidentialAddress(parsedStudent.address || '');
    }
  }, []);

  const handleSaveProfileChanges = async () => {
    if (!studentProfile) return;
    setIsSavingProfile(true);
    try {
      const updateProfileResponse = await ApiService.updateProfil({
        email: emailAddress || undefined,
        phone_number: telephoneNumber || undefined,
        address: residentialAddress || undefined,
      });
      if (updateProfileResponse.success) {
        const updatedStudentProfile = {
          ...studentProfile,
          email: emailAddress,
          phone_number: telephoneNumber,
          address: residentialAddress,
        };
        localStorage.setItem('student_data', JSON.stringify(updatedStudentProfile));
        setStudentProfile(updatedStudentProfile);
        displayFeedbackToast('Profil berhasil disimpan!', true);
      } else {
        displayFeedbackToast('Gagal menyimpan: ' + (updateProfileResponse.message || 'Coba lagi'));
      }
    } catch (saveError: any) {
      displayFeedbackToast('Error: ' + saveError.message);
    } finally {
      setIsSavingProfile(false);
    }
  };

  const studentInitials = studentProfile?.full_name
    ? studentProfile.full_name.substring(0, 2).toUpperCase()
    : studentProfile?.name
    ? studentProfile.name.substring(0, 2).toUpperCase()
    : 'AR';

  return (
    <>
      {feedbackToast && (
        <div style={{ position: 'fixed', top: 16, left: '50%', transform: 'translateX(-50%)', zIndex: 9999, maxWidth: 320, width: 'calc(100% - 32px)', padding: '12px 16px', borderRadius: 14, background: feedbackToast.isSuccess ? '#ECFDF5' : '#FFF1F2', border: `1.5px solid ${feedbackToast.isSuccess ? '#6EE7B7' : '#FCA5A5'}`, boxShadow: '0 8px 24px rgba(0,0,0,0.12)', display: 'flex', alignItems: 'center', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
          <div style={{ width: 28, height: 28, borderRadius: 8, background: feedbackToast.isSuccess ? '#10B981' : '#EF4444', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
              {feedbackToast.isSuccess ? <path d="M20 6L9 17l-5-5"/> : <><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></>}
            </svg>
          </div>
          <p style={{ margin: 0, fontSize: 13, color: feedbackToast.isSuccess ? '#065F46' : '#9B1C1C', fontFamily: 'Inter', lineHeight: 1.5 }}>{feedbackToast.messageText}</p>
        </div>
      )}
      <SubHeader title="Edit Profil" subtitle="Kelola informasi profil Anda" onBackPress={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 20, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: 24 }}>
              <div style={{ position: 'relative', marginBottom: 10 }}>
                <div style={{ width: 88, height: 88, borderRadius: '50%', background: `linear-gradient(135deg, ${COLOR_PRIMARY} 0%, ${COLOR_VIOLET} 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, fontWeight: 900, color: '#fff', fontFamily: 'Nunito' }}>
                  {studentInitials}
                </div>
              </div>
              <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 15, color: '#1E293B', margin: '0 0 2px' }}>
                {studentProfile?.full_name || studentProfile?.name || 'Siswa'}
              </p>
              <p style={{ fontSize: 12, color: '#94A3B8', margin: 0 }}>NIS: {studentProfile?.nis || '-'}</p>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
              <InputField
                label="Nama Lengkap"
                readOnly
                value={studentProfile?.full_name || studentProfile?.name || ''}
                onChange={() => {}}
                placeholder="Nama tidak dapat diubah di sini"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>}
              />
              <InputField
                label="Alamat Email"
                required
                value={emailAddress}
                onChange={setEmailAddress}
                placeholder="email@school.sch.id"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>}
              />
              <InputField
                label="No. HP"
                value={telephoneNumber}
                onChange={setTelephoneNumber}
                placeholder="08xxxxxxxxxx"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.7A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.36-.36a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15z"/></svg>}
              />
              <InputField
                label="Alamat"
                value={residentialAddress}
                onChange={setResidentialAddress}
                placeholder="Alamat tempat tinggal"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>}
              />
            </div>
            <p style={{ fontSize: 11, color: '#CBD5E1', marginTop: 16, marginBottom: 0 }}>Nama & NIS tidak dapat diubah mandiri · Hubungi admin sekolah</p>
          </div>
        </FadeUpAnimation>
        <FadeUpAnimation delayMilliseconds={100}>
          <button
            onClick={handleSaveProfileChanges}
            disabled={isSavingProfile}
            style={{
              width: '100%',
              padding: '16px',
              borderRadius: 18,
              border: 'none',
              cursor: isSavingProfile ? 'not-allowed' : 'pointer',
              background: isSavingProfile ? '#CBD5E1' : `linear-gradient(135deg, ${COLOR_PRIMARY} 0%, ${COLOR_VIOLET} 100%)`,
              boxShadow: isSavingProfile ? 'none' : `0 10px 28px rgba(79,70,229,0.4)`,
              color: '#fff',
              fontWeight: 900,
              fontSize: 16,
              fontFamily: 'Nunito',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              transition: 'all 0.3s',
            }}
          >
            {isSavingProfile ? (
              <>
                <svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg>
                Menyimpan...
              </>
            ) : (
              <>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                Simpan Perubahan
              </>
            )}
          </button>
        </FadeUpAnimation>
      </div>
    </>
  );
}
