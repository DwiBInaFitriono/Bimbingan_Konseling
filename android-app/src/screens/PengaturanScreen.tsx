import React, { useState, useEffect } from 'react';
import { Screen } from '../types';
import { COLOR_PRIMARY, COLOR_SUCCESS, COLOR_WARNING } from '../constants';
import { FadeUpAnimation } from '../components/Animations';
import { SubHeader } from '../components/SubHeader';

export function PengaturanScreen({ navigate }: { navigate: (targetScreen: Screen) => void }) {
  const [isNotificationPushEnabled, setIsNotificationPushEnabled] = useState(true);
  const [isDarkModeEnabled, setIsDarkModeEnabled] = useState(false);
  const [selectedLanguageCode, setSelectedLanguageCode] = useState('id');
  const [studentProfileData, setStudentProfileData] = useState<any>(null);

  useEffect(() => {
    const studentStorageData = localStorage.getItem('student_data');
    if (studentStorageData) {
      setStudentProfileData(JSON.parse(studentStorageData));
    }
  }, []);

  const ToggleSwitch = ({
    isToggleChecked,
    onToggleChange,
  }: {
    isToggleChecked: boolean;
    onToggleChange: (updatedState: boolean) => void;
  }) => (
    <button
      onClick={() => onToggleChange(!isToggleChecked)}
      style={{
        width: 48,
        height: 26,
        borderRadius: 13,
        background: isToggleChecked ? COLOR_PRIMARY : '#E2E8F0',
        border: 'none',
        cursor: 'pointer',
        position: 'relative',
        transition: 'background 0.3s',
        flexShrink: 0,
      }}
    >
      <div
        style={{
          position: 'absolute',
          top: 3,
          left: isToggleChecked ? 25 : 3,
          width: 20,
          height: 20,
          borderRadius: '50%',
          background: '#fff',
          boxShadow: '0 1px 4px rgba(0,0,0,0.25)',
          transition: 'left 0.3s',
        }}
      />
    </button>
  );

  const formattedClassWithMajor = studentProfileData?.school_class_name
    ? `${studentProfileData.school_class_name}${studentProfileData.school_class_major ? ' – ' + studentProfileData.school_class_major : ''}`
    : studentProfileData?.class?.school_class_name ?? '-';

  const profileInformationRows = [
    { infoLabel: 'Nama Lengkap', infoValue: studentProfileData?.full_name || studentProfileData?.name || '-' },
    { infoLabel: 'Email', infoValue: studentProfileData?.email || '-' },
    { infoLabel: 'NIS', infoValue: studentProfileData?.nis || '-' },
    { infoLabel: 'Kelas', infoValue: formattedClassWithMajor },
  ];

  const accountMetadataCards = [
    {
      metricIcon: (
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2" strokeLinecap="round">
          <rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
      ),
      metricTitle: 'Tgl Daftar',
      metricContent: '05 Agu 2026',
    },
    {
      metricIcon: (
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={COLOR_SUCCESS} strokeWidth="2" strokeLinecap="round">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
      ),
      metricTitle: 'Diperbarui',
      metricContent: '9 jam lalu',
    },
    {
      metricIcon: (
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={COLOR_WARNING} strokeWidth="2" strokeLinecap="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
      ),
      metricTitle: 'Status',
      metricContent: 'Aktif',
    },
  ];

  return (
    <>
      <SubHeader title="Pengaturan Akun" subtitle="Atur preferensi akun Anda" onBackPress={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Informasi Profil</p>
            </div>
            {profileInformationRows.map((infoRow, rowIndex, totalRows) => (
              <div key={rowIndex} style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', gap: 12, borderBottom: rowIndex < totalRows.length - 1 ? '1px solid #F8FAFC' : 'none' }}>
                <span style={{ fontSize: 13, color: '#64748B', fontWeight: 600 }}>{infoRow.infoLabel}</span>
                <span style={{ fontSize: 13, color: '#1E293B', fontWeight: 700, textAlign: 'right', maxWidth: '60%', wordBreak: 'break-all' }}>{infoRow.infoValue}</span>
              </div>
            ))}
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={80}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Preferensi</p>
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #F8FAFC' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Notifikasi Push</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Aktifkan pemberitahuan konseling</p>
              </div>
              <ToggleSwitch isToggleChecked={isNotificationPushEnabled} onToggleChange={setIsNotificationPushEnabled} />
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #F8FAFC' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Mode Gelap</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Tampilan gelap untuk layar</p>
              </div>
              <ToggleSwitch isToggleChecked={isDarkModeEnabled} onToggleChange={setIsDarkModeEnabled} />
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Bahasa</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Bahasa tampilan aplikasi</p>
              </div>
              <select
                value={selectedLanguageCode}
                onChange={changeEvent => setSelectedLanguageCode(changeEvent.target.value)}
                style={{ fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: '1.5px solid #E2E8F0', borderRadius: 10, padding: '6px 10px', outline: 'none' }}
              >
                <option value="id">Bahasa Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={160}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Informasi Akun</p>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', padding: '16px' }}>
              {accountMetadataCards.map((metadataCard, cardIndex) => (
                <div key={cardIndex} style={{ textAlign: 'center', padding: '4px' }}>
                  <div style={{ display: 'flex', justifyContent: 'center', marginBottom: 6 }}>{metadataCard.metricIcon}</div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 3px' }}>{metadataCard.metricTitle}</p>
                  <p style={{ fontSize: 12, color: '#1E293B', fontWeight: 800, margin: 0, fontFamily: 'Nunito' }}>{metadataCard.metricContent}</p>
                </div>
              ))}
            </div>
          </div>
        </FadeUpAnimation>
      </div>
    </>
  );
}
