import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { SubHeader } from '../components/SubHeader';

export function RiwayatScreen() {
  const [histTab, setHistTab] = useState<HistTab>('konseling')

  const konseling = [
    { id: 1, title: 'Konsultasi Perencanaan Karir & Kuliah RPL', date: '07 Agu 2026', guru: 'Rio S.Pd (Guru BK)', status: 'Disetujui', antrian: 3, perkiraan: '09:30' },
    { id: 2, title: 'Masalah Adaptasi Lingkungan Baru di Sekolah', date: '02 Agu 2026', guru: 'Rio S.Pd (Guru BK)', status: 'Selesai', antrian: null, perkiraan: null },
    { id: 3, title: 'Bimbingan Motivasi Belajar Semester Baru', date: '10 Jul 2026', guru: 'Ani M.Pd (Guru BK)', status: 'Selesai', antrian: null, perkiraan: null },
  ]
  const pelanggaran = [
    { id: 1, title: 'Terlambat masuk kelas', date: '05 Agu 2026', poin: 5, keterangan: 'Terlambat 15 menit tanpa keterangan' },
    { id: 2, title: 'Tidak memakai seragam lengkap', date: '22 Jul 2026', poin: 10, keterangan: 'Tidak memakai dasi dan sabuk' },
  ]
  const kasus = [
    { id: 1, title: 'Perkelahian antar siswa', date: '01 Jun 2026', status: 'Selesai', deskripsi: 'Insiden kecil di kantin, sudah diselesaikan secara kekeluargaan.' },
  ]
  const prestasi = [
    { id: 1, title: 'Juara 1 Lomba Web Design SMK', date: '15 Jul 2026', level: 'Sekolah', poin: 25 },
    { id: 2, title: 'Finalis Olimpiade Matematika', date: '10 Mar 2026', level: 'Kota', poin: 40 },
  ]

  const statusColor = (s: string) => s === 'Disetujui' ? { bg: '#EEF2FF', c: P } : s === 'Selesai' ? { bg: '#F0FDF4', c: T } : { bg: '#FFFBEB', c: AM }

  const tabCounts: Record<HistTab, number> = {
    konseling: konseling.length,
    pelanggaran: pelanggaran.length,
    kasus: kasus.length,
    prestasi: prestasi.length,
  }

  return (
    <>
      {/* Header */}
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '4px 20px 20px', position: 'relative', overflow: 'hidden', flexShrink: 0 }}>
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
        {histTab === 'konseling' && konseling.map((item, i) => {
          const sc = statusColor(item.status)
          return (
            <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12 }}>
                <div style={{ flex: 1, minWidth: 0 }}>
                  <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                    <div style={{ width: 8, height: 8, borderRadius: '50%', background: P, marginTop: 5, flexShrink: 0, animation: item.status === 'Disetujui' ? 'pulseDot 1.5s ease-in-out infinite' : 'none' }} />
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0, lineHeight: 1.4 }}>{item.title}</p>
                  </div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '6px 0 0 16px' }}>{item.date} · {item.guru}</p>
                  {item.antrian && (
                    <div style={{ display: 'flex', gap: 16, marginTop: 8, marginLeft: 16 }}>
                      <span style={{ fontSize: 11, color: '#64748B' }}>Antrian: <strong style={{ color: '#1E293B' }}>#{item.antrian}</strong></span>
                      <span style={{ fontSize: 11, color: '#64748B' }}>Perkiraan: <strong style={{ color: P }}>{item.perkiraan}</strong></span>
                    </div>
                  )}
                </div>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: sc.bg, color: sc.c, fontFamily: 'Nunito' }}>{item.status}</span>
              </div>
            </div>
          )
        })}

        {histTab === 'pelanggaran' && pelanggaran.map((item, i) => (
          <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(239,68,68,0.3)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.title}</p>
                  <span style={{ fontSize: 11, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#FFF1F2', color: RD, flexShrink: 0, marginLeft: 8 }}>-{item.poin} poin</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{item.date}</p>
                <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5 }}>{item.keterangan}</p>
              </div>
            </div>
          </div>
        ))}

        {histTab === 'kasus' && kasus.map((item, i) => {
          const sc = statusColor(item.status)
          return (
            <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12, marginBottom: 10 }}>
                <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.title}</p>
                <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: sc.bg, color: sc.c, fontFamily: 'Nunito' }}>{item.status}</span>
              </div>
              <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 8px' }}>{item.date}</p>
              <p style={{ fontSize: 12, color: '#64748B', margin: 0, lineHeight: 1.5, padding: '10px 12px', background: '#F8FAFC', borderRadius: 10 }}>{item.deskripsi}</p>
            </div>
          )
        })}

        {histTab === 'prestasi' && prestasi.map((item, i) => (
          <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
            <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
              <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${AM} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, boxShadow: '0 4px 12px rgba(245,158,11,0.35)' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.title}</p>
                  <span style={{ fontSize: 11, fontWeight: 900, padding: '3px 8px', borderRadius: 8, background: '#FEF3C7', color: '#D97706', flexShrink: 0, marginLeft: 8 }}>+{item.poin} poin</span>
                </div>
                <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 6px' }}>{item.date} · Tingkat {item.level}</p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </>
  )
}
