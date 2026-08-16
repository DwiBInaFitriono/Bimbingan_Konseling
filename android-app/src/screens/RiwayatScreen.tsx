import React, { useState, useEffect } from 'react';
import { ApiService } from '../services/api';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function RiwayatScreen() {
  const [histTab, setHistTab] = useState<HistTab>('konseling')
  const [data, setData] = useState({
    konseling: [] as any[],
    pelanggaran: [] as any[],
    kasus: [] as any[],
    prestasi: [] as any[]
  })
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const studentData = localStorage.getItem('student_data');
    if (!studentData) return;
    const studentId = JSON.parse(studentData).id;

    Promise.all([
      ApiService.getRiwayat(studentId, 'konseling'),
      ApiService.getRiwayat(studentId, 'pelanggaran'),
      ApiService.getRiwayat(studentId, 'kasus'),
      ApiService.getRiwayat(studentId, 'prestasi'),
    ]).then(([resK, resP, resKa, resPr]) => {
      setData({
        konseling: resK.success ? resK.data : [],
        pelanggaran: resP.success ? resP.data : [],
        kasus: resKa.success ? resKa.data : [],
        prestasi: resPr.success ? resPr.data : [],
      })
      setLoading(false)
    }).catch(err => {
      console.error(err)
      setLoading(false)
    })
  }, [])

  const statusColor = (s: string) => s === 'Disetujui' ? { bg: '#EEF2FF', c: P } : s === 'Selesai' ? { bg: '#F0FDF4', c: T } : { bg: '#FFFBEB', c: AM }

  const tabCounts: Record<HistTab, number> = {
    konseling: data.konseling.length,
    pelanggaran: data.pelanggaran.length,
    kasus: data.kasus.length,
    prestasi: data.prestasi.length,
  }

  return (
    <>
      {/* Header */}
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '16px 20px 20px', position: 'relative', overflow: 'hidden', flexShrink: 0 }}>
        <div style={{ position: 'absolute', top: -50, right: -50, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <FU d={0}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <div style={{ width: 36, height: 36, borderRadius: 12, background: 'rgba(255,255,255,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 18, fontFamily: 'Nunito', margin: 0, lineHeight: 1.2 }}>Riwayat Saya</h1>
              <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, margin: 0, marginTop: 2 }}>Rekap seluruh aktivitas BK</p>
            </div>
          </div>
        </FU>
      </div>

      {/* Tabs */}
      <div style={{ background: '#fff', borderBottom: '1px solid #F1F5F9', padding: '12px 16px', flexShrink: 0, display: 'flex', gap: 8, overflowX: 'auto' }}>
        {(['konseling', 'pelanggaran', 'kasus', 'prestasi'] as HistTab[]).map(t => {
          const active = histTab === t
          return (
            <button
              key={t}
              onClick={() => setHistTab(t)}
              style={{
                flexShrink: 0,
                padding: '7px 14px',
                borderRadius: 12,
                border: 'none',
                cursor: 'pointer',
                fontSize: 12,
                fontWeight: 800,
                fontFamily: 'Nunito',
                background: active ? P : '#F1F5F9',
                color: active ? '#fff' : '#64748B',
                boxShadow: active ? `0 4px 14px rgba(79,70,229,0.35)` : 'none',
                transition: 'all 0.2s',
                textTransform: 'capitalize',
                display: 'flex',
                alignItems: 'center',
                gap: 6,
              }}
            >
              {t}
              <span style={{ fontSize: 10, fontWeight: 900, padding: '1px 6px', borderRadius: 8, background: active ? 'rgba(255,255,255,0.25)' : '#E2E8F0', color: active ? '#fff' : '#64748B' }}>
                {tabCounts[t]}
              </span>
            </button>
          )
        })}
      </div>

      {/* Content */}
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: '14px 16px 24px', display: 'flex', flexDirection: 'column', gap: 10 }}>
        {histTab === 'konseling' && data.konseling.map((item, i) => {
          const statusStr = item.status === 'menunggu' ? 'Menunggu' : item.status === 'disetujui' ? 'Disetujui' : item.status === 'ditolak' ? 'Ditolak' : item.status === 'selesai' ? 'Selesai' : item.status === 'dibatalkan' ? 'Dibatalkan' : item.status;
          const sc = statusColor(statusStr)
          return (
            <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12 }}>
                <div style={{ flex: 1, minWidth: 0 }}>
                  <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                    <div style={{ width: 8, height: 8, borderRadius: '50%', background: P, marginTop: 5, flexShrink: 0, animation: statusStr === 'Disetujui' ? 'pulseDot 1.5s ease-in-out infinite' : 'none' }} />
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0, lineHeight: 1.4 }}>{item.topic || item.type || 'Sesi Konseling'}</p>
                  </div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '6px 0 0 16px' }}>{item.requested_date} {item.requested_time ? `· ${item.requested_time}` : ''} {item.counselor_name ? `· ${item.counselor_name}` : ''}</p>
                  {item.description && <p style={{ fontSize: 12, color: '#64748B', margin: '4px 0 0 16px', lineHeight: 1.5 }}>{item.description}</p>}
                </div>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: sc.bg, color: sc.c, fontFamily: 'Nunito' }}>{statusStr}</span>
              </div>
            </div>
          )
        })}

        {histTab === 'pelanggaran' && data.pelanggaran.map((item, i) => (
          <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(239,68,68,0.3)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.violation || item.category_name || 'Pelanggaran'}</p>
                  <span style={{ fontSize: 11, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#FFF1F2', color: RD, flexShrink: 0, marginLeft: 8 }}>+{item.point_number || 0} poin</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{item.violation_date || item.created_at?.split('T')[0]}</p>
                <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{item.description}</p>
              </div>
            </div>
          </div>
        ))}

        {histTab === 'kasus' && data.kasus.map((item, i) => {
          const statusStr = item.status === 'completed' ? 'Selesai' : 'Diproses';
          const sc = statusColor(statusStr)
          return (
            <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12, marginBottom: 10 }}>
                <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.case_name || 'Kasus'}</p>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: sc.bg, color: sc.c, fontFamily: 'Nunito' }}>{statusStr}</span>
              </div>
              <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 8px' }}>{item.created_at?.split('T')[0]}</p>
              <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5, padding: '10px 12px', background: '#F8FAFC', borderRadius: 10 }}>{item.description || '-'}</p>
            </div>
          )
        })}

        {histTab === 'prestasi' && data.prestasi.map((item, i) => (
          <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${AM} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(245,158,11,0.35)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.achievement_name}</p>
                  <span style={{ fontSize: 10, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#EEF2FF', color: P, flexShrink: 0, marginLeft: 8, textTransform: 'capitalize' }}>{item.achievement_status || 'Aktif'}</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{item.achievement_date} {item.achievement_level ? `· Tingkat ${item.achievement_level}` : ''}</p>
                {item.description && <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{item.description}</p>}
              </div>
            </div>
          </div>
        ))}
      </div>
    </>
  )
}

// ─── PROFIL SCREEN ────────────────────────────────────────────────────────────
