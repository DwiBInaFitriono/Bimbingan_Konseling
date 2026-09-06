import React, { useState, useEffect } from 'react';
import { ApiService } from '../services/api';
import { HistTab } from '../types';
import {
  COLOR_PRIMARY,
  COLOR_SUCCESS,
  COLOR_WARNING,
  COLOR_DANGER,
  COLOR_INDIGO,
} from '../constants';
import { FadeUpAnimation } from '../components/Animations';

export function RiwayatScreen() {
  const [selectedHistoryCategory, setSelectedHistoryCategory] = useState<HistTab>('konseling');
  const [studentHistoryRecords, setStudentHistoryRecords] = useState<{
    konseling: any[];
    pelanggaran: any[];
    kasus: any[];
    prestasi: any[];
  }>({
    konseling: [],
    pelanggaran: [],
    kasus: [],
    prestasi: [],
  });

  useEffect(() => {
    const studentStorageData = localStorage.getItem('student_data');
    if (!studentStorageData) return;

    Promise.all([
      ApiService.getRiwayat('konseling'),
      ApiService.getRiwayat('pelanggaran'),
      ApiService.getRiwayat('kasus'),
      ApiService.getRiwayat('prestasi'),
    ])
      .then(([counselingResult, violationResult, caseResult, achievementResult]) => {
        setStudentHistoryRecords({
          konseling: counselingResult.success ? counselingResult.data : [],
          pelanggaran: violationResult.success ? violationResult.data : [],
          kasus: caseResult.success ? caseResult.data : [],
          prestasi: achievementResult.success ? achievementResult.data : [],
        });
      })
      .catch((historyFetchError: any) => {
        console.error('Gagal mengambil data riwayat siswa:', historyFetchError);
      });
  }, []);

  const getHistoryStatusBadgeColor = (statusName: string) => {
    if (statusName === 'Disetujui') {
      return { backgroundColor: '#EEF2FF', textColor: COLOR_PRIMARY };
    }
    if (statusName === 'Selesai') {
      return { backgroundColor: '#F0FDF4', textColor: COLOR_SUCCESS };
    }
    return { backgroundColor: '#FFFBEB', textColor: COLOR_WARNING };
  };

  const categoryItemCounts: Record<HistTab, number> = {
    konseling: studentHistoryRecords.konseling.length,
    pelanggaran: studentHistoryRecords.pelanggaran.length,
    kasus: studentHistoryRecords.kasus.length,
    prestasi: studentHistoryRecords.prestasi.length,
  };

  const availableHistoryCategories: HistTab[] = ['konseling', 'pelanggaran', 'kasus', 'prestasi'];

  return (
    <>
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${COLOR_PRIMARY} 55%, ${COLOR_INDIGO} 100%)`, padding: '16px 20px 20px', position: 'relative', overflow: 'hidden', flexShrink: 0 }}>
        <div style={{ position: 'absolute', top: -50, right: -50, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <div style={{ width: 36, height: 36, borderRadius: 12, background: 'rgba(255,255,255,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 18, fontFamily: 'Nunito', margin: 0, lineHeight: 1.2 }}>Riwayat Saya</h1>
              <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, margin: 0, marginTop: 2 }}>Rekap seluruh aktivitas BK</p>
            </div>
          </div>
        </FadeUpAnimation>
      </div>

      <div style={{ background: '#fff', borderBottom: '1px solid #F1F5F9', padding: '12px 16px', flexShrink: 0, display: 'flex', gap: 8, overflowX: 'auto' }}>
        {availableHistoryCategories.map(categoryKey => {
          const isCategoryActive = selectedHistoryCategory === categoryKey;
          return (
            <button
              key={categoryKey}
              onClick={() => setSelectedHistoryCategory(categoryKey)}
              style={{
                flexShrink: 0,
                padding: '7px 14px',
                borderRadius: 12,
                border: 'none',
                cursor: 'pointer',
                fontSize: 12,
                fontWeight: 800,
                fontFamily: 'Nunito',
                background: isCategoryActive ? COLOR_PRIMARY : '#F1F5F9',
                color: isCategoryActive ? '#fff' : '#64748B',
                boxShadow: isCategoryActive ? `0 4px 14px rgba(79,70,229,0.35)` : 'none',
                transition: 'all 0.2s',
                textTransform: 'capitalize',
                display: 'flex',
                alignItems: 'center',
                gap: 6,
              }}
            >
              {categoryKey}
              <span style={{ fontSize: 10, fontWeight: 900, padding: '1px 6px', borderRadius: 8, background: isCategoryActive ? 'rgba(255,255,255,0.25)' : '#E2E8F0', color: isCategoryActive ? '#fff' : '#64748B' }}>
                {categoryItemCounts[categoryKey]}
              </span>
            </button>
          );
        })}
      </div>

      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: '14px 16px 24px', display: 'flex', flexDirection: 'column', gap: 10 }}>
        {selectedHistoryCategory === 'konseling' && studentHistoryRecords.konseling.map((counselingRecord, recordIndex) => {
          const formattedStatusText =
            counselingRecord.status === 'menunggu'
              ? 'Menunggu'
              : counselingRecord.status === 'disetujui'
              ? 'Disetujui'
              : counselingRecord.status === 'ditolak'
              ? 'Ditolak'
              : counselingRecord.status === 'selesai'
              ? 'Selesai'
              : counselingRecord.status === 'dibatalkan'
              ? 'Dibatalkan'
              : counselingRecord.status;
          const statusBadgeStyle = getHistoryStatusBadgeColor(formattedStatusText);
          return (
            <div key={counselingRecord.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${recordIndex * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12 }}>
                <div style={{ flex: 1, minWidth: 0 }}>
                  <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                    <div style={{ width: 8, height: 8, borderRadius: '50%', background: COLOR_PRIMARY, marginTop: 5, flexShrink: 0, animation: formattedStatusText === 'Disetujui' ? 'pulseDot 1.5s ease-in-out infinite' : 'none' }} />
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0, lineHeight: 1.4 }}>{counselingRecord.topic || counselingRecord.type || 'Sesi Konseling'}</p>
                  </div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '6px 0 0 16px' }}>{counselingRecord.requested_date} {counselingRecord.requested_time ? `· ${counselingRecord.requested_time}` : ''} {counselingRecord.counselor_name ? `· ${counselingRecord.counselor_name}` : ''}</p>
                  {counselingRecord.description && <p style={{ fontSize: 12, color: '#64748B', margin: '4px 0 0 16px', lineHeight: 1.5 }}>{counselingRecord.description}</p>}
                </div>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: statusBadgeStyle.backgroundColor, color: statusBadgeStyle.textColor, fontFamily: 'Nunito' }}>{formattedStatusText}</span>
              </div>
            </div>
          );
        })}

        {selectedHistoryCategory === 'pelanggaran' && studentHistoryRecords.pelanggaran.map((violationRecord, recordIndex) => (
          <div key={violationRecord.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${recordIndex * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(239,68,68,0.3)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{violationRecord.violation || violationRecord.category_name || 'Pelanggaran'}</p>
                  <span style={{ fontSize: 11, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#FFF1F2', color: COLOR_DANGER, flexShrink: 0, marginLeft: 8 }}>+{violationRecord.point_number || 0} poin</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{violationRecord.violation_date || violationRecord.created_at?.split('T')[0]}</p>
                <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{violationRecord.description}</p>
              </div>
            </div>
          </div>
        ))}

        {selectedHistoryCategory === 'kasus' && studentHistoryRecords.kasus.map((caseRecord, recordIndex) => {
          const formattedCaseStatus = caseRecord.status === 'completed' ? 'Selesai' : 'Diproses';
          const statusBadgeStyle = getHistoryStatusBadgeColor(formattedCaseStatus);
          return (
            <div key={caseRecord.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${recordIndex * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12, marginBottom: 10 }}>
                <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{caseRecord.case_name || 'Kasus'}</p>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: statusBadgeStyle.backgroundColor, color: statusBadgeStyle.textColor, fontFamily: 'Nunito' }}>{formattedCaseStatus}</span>
              </div>
              <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 8px' }}>{caseRecord.created_at?.split('T')[0]}</p>
              <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5, padding: '10px 12px', background: '#F8FAFC', borderRadius: 10 }}>{caseRecord.description || '-'}</p>
            </div>
          );
        })}

        {selectedHistoryCategory === 'prestasi' && studentHistoryRecords.prestasi.map((achievementRecord, recordIndex) => (
          <div key={achievementRecord.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${recordIndex * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${COLOR_WARNING} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(245,158,11,0.35)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{achievementRecord.achievement_name}</p>
                  <span style={{ fontSize: 10, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#EEF2FF', color: COLOR_PRIMARY, flexShrink: 0, marginLeft: 8, textTransform: 'capitalize' }}>{achievementRecord.achievement_status || 'Aktif'}</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{achievementRecord.achievement_date} {achievementRecord.achievement_level ? `· Tingkat ${achievementRecord.achievement_level}` : ''}</p>
                {achievementRecord.description && <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{achievementRecord.description}</p>}
              </div>
            </div>
          </div>
        ))}
      </div>
    </>
  );
}
