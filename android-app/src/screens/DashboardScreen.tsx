import React, { useState, useEffect } from 'react';
import { ApiService } from '../services/api';
import { Screen, HistTab } from '../types';
import { COLOR_PRIMARY, COLOR_VIOLET, COLOR_SUCCESS, COLOR_WARNING, COLOR_DANGER, COLOR_INDIGO } from '../constants';
import { FadeUpAnimation } from '../components/Animations';

export function DashboardScreen({ navigate }: { navigate: (targetScreen: Screen) => void }) {
  const [activeHistoryTab, setActiveHistoryTab] = useState<HistTab>('konseling');
  const [studentProfileData, setStudentProfileData] = useState<any>(null);
  const [counselingHistoryList, setCounselingHistoryList] = useState<any[]>([]);
  const [violationHistoryList, setViolationHistoryList] = useState<any[]>([]);
  const [caseHistoryList, setCaseHistoryList] = useState<any[]>([]);
  const [achievementHistoryList, setAchievementHistoryList] = useState<any[]>([]);
  const [isLoadingDashboardData, setIsLoadingDashboardData] = useState(true);

  useEffect(() => {
    const rawStudentData = localStorage.getItem('student_data');
    if (rawStudentData) {
      const parsedStudentData = JSON.parse(rawStudentData);
      setStudentProfileData(parsedStudentData);

      Promise.all([
        ApiService.getRiwayat('konseling'),
        ApiService.getRiwayat('pelanggaran'),
        ApiService.getRiwayat('kasus'),
        ApiService.getRiwayat('prestasi')
      ]).then(([counselingResponse, violationResponse, caseResponse, achievementResponse]) => {
        if (counselingResponse.success) setCounselingHistoryList(counselingResponse.data || []);
        if (violationResponse.success) setViolationHistoryList(violationResponse.data || []);
        if (caseResponse.success) setCaseHistoryList(caseResponse.data || []);
        if (achievementResponse.success) setAchievementHistoryList(achievementResponse.data || []);
        setIsLoadingDashboardData(false);
      }).catch(fetchError => {
        console.error('Fetch dashboard data error:', fetchError);
        setIsLoadingDashboardData(false);
      });
    } else {
      setIsLoadingDashboardData(false);
    }
  }, []);

  const totalViolationPoints = violationHistoryList.reduce(
    (accumulatedPoints, violationItem) => accumulatedPoints + (Number(violationItem.point_number) || 0),
    0
  );
  const disciplineStatusText = totalViolationPoints === 0 ? 'Sangat Baik' : totalViolationPoints <= 20 ? 'Aman' : totalViolationPoints <= 50 ? 'Peringatan' : 'Kritis';
  const disciplineStatusGradientStyle = totalViolationPoints <= 20
    ? `linear-gradient(135deg, ${COLOR_SUCCESS} 0%, #059669 100%)`
    : totalViolationPoints <= 50
    ? `linear-gradient(135deg, ${COLOR_WARNING} 0%, #D97706 100%)`
    : `linear-gradient(135deg, ${COLOR_DANGER} 0%, #DC2626 100%)`;

  const getStatusBadgeStyle = (statusName: string) => {
    const normalizedStatus = (statusName || '').toLowerCase();
    if (normalizedStatus === 'disetujui' || normalizedStatus === 'approved') return { backgroundColor: '#EEF2FF', textColor: COLOR_PRIMARY };
    if (normalizedStatus === 'selesai' || normalizedStatus === 'completed') return { backgroundColor: '#F0FDF4', textColor: COLOR_SUCCESS };
    if (normalizedStatus === 'ditolak' || normalizedStatus === 'dibatalkan' || normalizedStatus === 'batal') return { backgroundColor: '#FFF1F2', textColor: COLOR_DANGER };
    return { backgroundColor: '#FFFBEB', textColor: COLOR_WARNING };
  };

  const formatStatusLabel = (rawStatus: string) => {
    switch (rawStatus) {
      case 'menunggu':
      case 'pending':
        return 'Menunggu';
      case 'disetujui':
      case 'approved':
        return 'Disetujui';
      case 'ditolak':
        return 'Ditolak';
      case 'selesai':
      case 'completed':
        return 'Selesai';
      case 'dibatalkan':
        return 'Dibatalkan';
      default:
        return rawStatus || 'Menunggu';
    }
  };

  return (
    <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9' }}>
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${COLOR_PRIMARY} 55%, ${COLOR_INDIGO} 100%)`, padding: '16px 20px 28px', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -60, right: -60, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', top: 0, right: 30, width: 110, height: 110, border: '1px solid rgba(255,255,255,0.05)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', bottom: -20, left: -30, width: 120, height: 120, background: 'rgba(255,255,255,0.04)', borderRadius: '50%', pointerEvents: 'none' }} />

        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 16 }}>
            <div>
              <p style={{ color: 'rgba(255,255,255,0.85)', fontSize: 13, margin: 0, fontWeight: 500 }}>Selamat datang kembali</p>
              <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 22, fontFamily: 'Nunito', margin: '4px 0 2px', letterSpacing: -0.5 }}>{studentProfileData?.name || 'Siswa'}</h1>
              <p style={{ color: 'rgba(255,255,255,0.75)', fontSize: 12, margin: 0 }}>NIS: {studentProfileData?.nis || '-'}</p>
            </div>
            <button
              onClick={() => navigate('profil')}
              style={{ width: 40, height: 40, borderRadius: 14, background: 'rgba(255,255,255,0.15)', border: '1px solid rgba(255,255,255,0.2)', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative', flexShrink: 0 }}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
              </svg>
              <div style={{ position: 'absolute', top: 8, right: 8, width: 8, height: 8, background: COLOR_DANGER, borderRadius: '50%', border: '1.5px solid rgba(91,33,182)' }} />
            </button>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={80}>
          <button
            onClick={() => navigate('jadwal')}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '10px 18px', borderRadius: 14, background: 'rgba(255,255,255,0.18)', border: '1px solid rgba(255,255,255,0.28)', cursor: 'pointer', color: '#fff', fontWeight: 800, fontSize: 14, fontFamily: 'Nunito', backdropFilter: 'blur(8px)', boxShadow: '0 4px 16px rgba(0,0,0,0.15)', transition: 'transform 0.15s' }}
            onMouseDown={mouseEvent => (mouseEvent.currentTarget.style.transform = 'scale(0.96)')}
            onMouseUp={mouseEvent => (mouseEvent.currentTarget.style.transform = 'scale(1)')}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/><circle cx="12" cy="16" r="1.5" fill="white"/></svg>
            Ajukan Konseling
          </button>
        </FadeUpAnimation>
      </div>

      <div style={{ marginTop: -16, overflowX: 'auto', paddingBottom: 4 }}>
        <div style={{ display: 'flex', gap: 12, padding: '0 16px', width: 'max-content' }}>
          <FadeUpAnimation delayMilliseconds={100}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: disciplineStatusGradientStyle, boxShadow: `0 8px 24px rgba(16,185,129,0.35)`, position: 'relative', overflow: 'hidden' }}>
              <div style={{ position: 'absolute', top: -20, right: -20, width: 90, height: 90, background: 'rgba(255,255,255,0.1)', borderRadius: '50%' }} />
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: 'rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <span style={{ color: 'rgba(255,255,255,0.85)', fontSize: 11, fontWeight: 700, lineHeight: 1.3 }}>Status Disiplin</span>
              </div>
              <div style={{ color: '#fff', fontWeight: 900, fontSize: 24, fontFamily: 'Nunito', lineHeight: 1 }}>{disciplineStatusText}</div>
              <div style={{ marginTop: 4, color: 'rgba(255,255,255,0.7)', fontSize: 11 }}>{totalViolationPoints} poin pelanggaran</div>
              <div style={{ marginTop: 8, height: 4, borderRadius: 4, background: 'rgba(255,255,255,0.2)' }}>
                <div style={{ width: `${Math.min(100, Math.max(10, totalViolationPoints))}%`, height: '100%', borderRadius: 4, background: 'rgba(255,255,255,0.8)' }} />
              </div>
            </div>
          </FadeUpAnimation>

          <FadeUpAnimation delayMilliseconds={150}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2.5" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Total Konseling</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>{counselingHistoryList.length}</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}><span style={{ color: COLOR_PRIMARY, fontWeight: 700 }}>Total sesi</span></div>
            </div>
          </FadeUpAnimation>

          <FadeUpAnimation delayMilliseconds={200}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#FFF1F2', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_DANGER} strokeWidth="2.5" strokeLinecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Kasus</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>{caseHistoryList.length}</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>{caseHistoryList.length === 0 ? 'Tidak ada kasus aktif' : `${caseHistoryList.length} kasus tercatat`}</div>
            </div>
          </FadeUpAnimation>

          <FadeUpAnimation delayMilliseconds={250}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)', marginRight: 16 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#F0FDF4', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_SUCCESS} strokeWidth="2.5" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Prestasi</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>{achievementHistoryList.length}</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>Prestasi tercatat</div>
            </div>
          </FadeUpAnimation>
        </div>
      </div>

      <div style={{ padding: '20px 16px 24px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 }}>
          <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 16, color: '#1E293B', margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Riwayat Saya
          </h2>
          <button
            onClick={() => navigate('riwayat')}
            style={{ background: 'none', border: 'none', cursor: 'pointer', color: COLOR_PRIMARY, fontSize: 13, fontWeight: 700, fontFamily: 'Nunito' }}
          >
            Lihat semua
          </button>
        </div>

        <div style={{ display: 'flex', gap: 8, marginBottom: 16, overflowX: 'auto', paddingBottom: 2 }}>
          {(['konseling', 'pelanggaran', 'kasus', 'prestasi'] as HistTab[]).map(tabKey => (
            <button
              key={tabKey}
              onClick={() => setActiveHistoryTab(tabKey)}
              style={{
                flexShrink: 0,
                padding: '7px 14px',
                borderRadius: 12,
                border: 'none',
                cursor: 'pointer',
                fontSize: 12,
                fontWeight: 800,
                fontFamily: 'Nunito',
                background: activeHistoryTab === tabKey ? COLOR_PRIMARY : '#fff',
                color: activeHistoryTab === tabKey ? '#fff' : '#64748B',
                boxShadow: activeHistoryTab === tabKey ? `0 4px 14px rgba(79,70,229,0.4)` : '0 1px 4px rgba(0,0,0,0.06)',
                transition: 'all 0.2s',
                textTransform: 'capitalize',
              }}
            >
              {tabKey}
            </button>
          ))}
        </div>

        {activeHistoryTab === 'konseling' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {counselingHistoryList.length === 0 ? (
              <p style={{ textAlign: 'center', fontSize: 12, color: '#94A3B8', margin: '20px 0' }}>Tidak ada riwayat konseling</p>
            ) : counselingHistoryList.map((counselingItem, itemIndex) => {
              const formattedStatus = formatStatusLabel(counselingItem.status);
              const statusStyle = getStatusBadgeStyle(formattedStatus);
              return (
                <div key={counselingItem.id || itemIndex} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${itemIndex * 60}ms both` }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12 }}>
                    <div style={{ flex: 1, minWidth: 0 }}>
                      <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                        <div style={{ width: 8, height: 8, borderRadius: '50%', background: COLOR_PRIMARY, marginTop: 5, flexShrink: 0, animation: formattedStatus === 'Disetujui' ? 'pulseDot 1.5s ease-in-out infinite' : 'none' }} />
                        <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0, lineHeight: 1.4 }}>{counselingItem.topic || counselingItem.note || counselingItem.type || 'Sesi Konseling'}</p>
                      </div>
                      <p style={{ fontSize: 11, color: '#94A3B8', margin: '6px 0 0 16px' }}>
                        {counselingItem.requested_date || counselingItem.schedule_date || '-'} {counselingItem.requested_time || counselingItem.schedule_time ? `· ${counselingItem.requested_time || counselingItem.schedule_time}` : ''} {counselingItem.counselor_name ? `· ${counselingItem.counselor_name}` : ''}
                      </p>
                      {counselingItem.description && <p style={{ fontSize: 12, color: '#64748B', margin: '4px 0 0 16px', lineHeight: 1.5 }}>{counselingItem.description}</p>}
                    </div>
                    <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: statusStyle.backgroundColor, color: statusStyle.textColor, fontFamily: 'Nunito' }}>{formattedStatus}</span>
                  </div>
                </div>
              );
            })}
          </div>
        )}

        {activeHistoryTab === 'pelanggaran' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {violationHistoryList.length === 0 ? (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', paddingTop: 30, paddingBottom: 30 }}>
                <div style={{ width: 56, height: 56, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 10 }}>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="1.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </div>
                <p style={{ fontWeight: 700, color: '#475569', fontSize: 14, margin: 0, fontFamily: 'Nunito' }}>Tidak ada data pelanggaran</p>
                <p style={{ fontSize: 12, color: '#64748B', marginTop: 4 }}>Belum ada catatan pelanggaran yang terdaftar.</p>
              </div>
            ) : violationHistoryList.map((violationItem, itemIndex) => (
              <div key={violationItem.id || itemIndex} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${itemIndex * 60}ms both` }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                  <div style={{ width: 44, height: 44, borderRadius: 14, background: 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(239,68,68,0.3)' }}>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                  </div>
                  <div style={{ flex: 1 }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                      <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{violationItem.violation || violationItem.category_name || 'Pelanggaran'}</p>
                      <span style={{ fontSize: 11, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#FFF1F2', color: COLOR_DANGER, flexShrink: 0, marginLeft: 8 }}>+{violationItem.point_number || 0} poin</span>
                    </div>
                    <p style={{ fontSize: 11, color: '#64748B', margin: '4px 0 6px' }}>{violationItem.violation_date || violationItem.created_at?.split('T')[0]}</p>
                    {violationItem.description && <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{violationItem.description}</p>}
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

        {activeHistoryTab === 'kasus' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {caseHistoryList.length === 0 ? (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', paddingTop: 30, paddingBottom: 30 }}>
                <div style={{ width: 56, height: 56, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 10 }}>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="1.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </div>
                <p style={{ fontWeight: 700, color: '#475569', fontSize: 14, margin: 0, fontFamily: 'Nunito' }}>Tidak ada kasus aktif</p>
                <p style={{ fontSize: 12, color: '#64748B', marginTop: 4 }}>Belum ada catatan kasus yang terdaftar.</p>
              </div>
            ) : caseHistoryList.map((caseItem, itemIndex) => {
              const formattedCaseStatus = caseItem.status === 'completed' ? 'Selesai' : 'Diproses';
              const statusStyle = getStatusBadgeStyle(formattedCaseStatus);
              return (
                <div key={caseItem.id || itemIndex} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${itemIndex * 60}ms both` }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12, marginBottom: 8 }}>
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{caseItem.case_name || 'Kasus'}</p>
                    <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: statusStyle.backgroundColor, color: statusStyle.textColor, fontFamily: 'Nunito' }}>{formattedCaseStatus}</span>
                  </div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 8px' }}>{caseItem.created_at?.split('T')[0]}</p>
                  <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5, padding: '10px 12px', background: '#F8FAFC', borderRadius: 10 }}>{caseItem.description || '-'}</p>
                </div>
              );
            })}
          </div>
        )}

        {activeHistoryTab === 'prestasi' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {achievementHistoryList.length === 0 ? (
              <p style={{ textAlign: 'center', fontSize: 12, color: '#94A3B8', margin: '20px 0' }}>Tidak ada riwayat prestasi</p>
            ) : achievementHistoryList.map((achievementItem, itemIndex) => (
              <div key={achievementItem.id || itemIndex} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${itemIndex * 60}ms both` }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                  <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${COLOR_WARNING} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(245,158,11,0.35)' }}>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
                  </div>
                  <div style={{ flex: 1 }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                      <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{achievementItem.achievement_name}</p>
                      <span style={{ fontSize: 10, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#EEF2FF', color: COLOR_PRIMARY, flexShrink: 0, marginLeft: 8, textTransform: 'capitalize' }}>{achievementItem.achievement_status || 'Terverifikasi'}</span>
                    </div>
                    <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{achievementItem.achievement_date || achievementItem.date} {achievementItem.achievement_level ? `· Tingkat ${achievementItem.achievement_level}` : ''}</p>
                    {achievementItem.description && <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{achievementItem.description}</p>}
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

