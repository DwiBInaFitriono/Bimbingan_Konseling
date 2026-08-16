import React, { useState, useEffect } from 'react';
import { ApiService } from '../services/api';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function DashboardScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [histTab, setHistTab] = useState<HistTab>('konseling')
  const [student, setStudent] = useState<any>(null)
  const [konseling, setKonseling] = useState<any[]>([])
  const [prestasi, setPrestasi] = useState<any[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const data = localStorage.getItem('student_data');
    if (data) {
      const parsed = JSON.parse(data);
      setStudent(parsed);
      
      // Fetch dynamic data
      Promise.all([
        ApiService.getRiwayat(parsed.id, 'konseling'),
        ApiService.getRiwayat(parsed.id, 'prestasi')
      ]).then(([resK, resP]) => {
        if (resK.success) setKonseling(resK.data || []);
        if (resP.success) setPrestasi(resP.data || []);
        setLoading(false);
      }).catch(err => {
        console.error(err);
        setLoading(false);
      });
    } else {
      setLoading(false);
    }
  }, [])

  const statusColor = (s: string) => s === 'Disetujui' ? { bg: '#EEF2FF', c: P } : s === 'Selesai' ? { bg: '#F0FDF4', c: T } : { bg: '#FFFBEB', c: AM }

  return (
    <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9' }}>
      {/* Hero header */}
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '16px 20px 28px', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -60, right: -60, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', top: 0, right: 30, width: 110, height: 110, border: '1px solid rgba(255,255,255,0.05)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', bottom: -20, left: -30, width: 120, height: 120, background: 'rgba(255,255,255,0.04)', borderRadius: '50%', pointerEvents: 'none' }} />

        <FU d={0}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 16 }}>
            <div>
              <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 13, margin: 0, fontWeight: 500 }}>Selamat datang kembali 👋</p>
              <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 22, fontFamily: 'Nunito', margin: '4px 0 2px', letterSpacing: -0.5 }}>{student?.name || 'Siswa'}</h1>
              <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 12, margin: 0 }}>NIS: {student?.nis || '-'}</p>
            </div>
            <button
              style={{ width: 40, height: 40, borderRadius: 14, background: 'rgba(255,255,255,0.15)', border: '1px solid rgba(255,255,255,0.2)', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative', flexShrink: 0 }}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
              </svg>
              <div style={{ position: 'absolute', top: 8, right: 8, width: 8, height: 8, background: RD, borderRadius: '50%', border: '1.5px solid rgba(91,33,182)' }} />
            </button>
          </div>
        </FU>

        <FU d={80}>
          <button
            onClick={() => navigate('jadwal')}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '10px 18px', borderRadius: 14, background: 'rgba(255,255,255,0.18)', border: '1px solid rgba(255,255,255,0.28)', cursor: 'pointer', color: '#fff', fontWeight: 800, fontSize: 14, fontFamily: 'Nunito', backdropFilter: 'blur(8px)', boxShadow: '0 4px 16px rgba(0,0,0,0.15)', transition: 'transform 0.15s' }}
            onMouseDown={e => (e.currentTarget.style.transform = 'scale(0.96)')}
            onMouseUp={e => (e.currentTarget.style.transform = 'scale(1)')}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/><circle cx="12" cy="16" r="1.5" fill="white"/></svg>
            Ajukan Konseling
          </button>
        </FU>
      </div>

      {/* Stats scroll row */}
      <div style={{ marginTop: -16, overflowX: 'auto', paddingBottom: 4 }}>
        <div style={{ display: 'flex', gap: 12, padding: '0 16px', width: 'max-content' }}>
          {/* Disiplin card (teal) */}
          <FU d={100}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: `linear-gradient(135deg, ${T} 0%, #059669 100%)`, boxShadow: `0 8px 24px rgba(16,185,129,0.35)`, position: 'relative', overflow: 'hidden' }}>
              <div style={{ position: 'absolute', top: -20, right: -20, width: 90, height: 90, background: 'rgba(255,255,255,0.1)', borderRadius: '50%' }} />
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: 'rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <span style={{ color: 'rgba(255,255,255,0.85)', fontSize: 11, fontWeight: 700, lineHeight: 1.3 }}>Status Disiplin</span>
              </div>
              <div style={{ color: '#fff', fontWeight: 900, fontSize: 24, fontFamily: 'Nunito', lineHeight: 1 }}>Aman</div>
              <div style={{ marginTop: 4, color: 'rgba(255,255,255,0.7)', fontSize: 11 }}>15 poin pelanggaran</div>
              <div style={{ marginTop: 8, height: 4, borderRadius: 4, background: 'rgba(255,255,255,0.2)' }}>
                <div style={{ width: '15%', height: '100%', borderRadius: 4, background: 'rgba(255,255,255,0.7)' }} />
              </div>
            </div>
          </FU>

          {/* Konseling card */}
          <FU d={150}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Total Konseling</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>{konseling.length}</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}><span style={{ color: P, fontWeight: 700 }}>Total sesi</span></div>
            </div>
          </FU>

          {/* Kasus card */}
          <FU d={200}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#FFF1F2', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2.5" strokeLinecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Kasus</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>0</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>Tidak ada kasus aktif</div>
            </div>
          </FU>

          {/* Prestasi card */}
          <FU d={250}>
            <div style={{ width: 148, borderRadius: 20, padding: 16, background: '#fff', boxShadow: '0 2px 12px rgba(0,0,0,0.07)', marginRight: 16 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
                <div style={{ width: 28, height: 28, borderRadius: 10, background: '#F0FDF4', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2.5" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
                </div>
                <span style={{ color: '#64748B', fontSize: 11, fontWeight: 700 }}>Prestasi</span>
              </div>
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>{prestasi.length}</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>Prestasi tercatat</div>
            </div>
          </FU>
        </div>
      </div>

      {/* Riwayat section */}
      <div style={{ padding: '20px 16px 24px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 }}>
          <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 16, color: '#1E293B', margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Riwayat Saya
          </h2>
          <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: P, fontSize: 13, fontWeight: 700, fontFamily: 'Nunito' }}>Lihat semua</button>
        </div>

        {/* Tabs */}
        <div style={{ display: 'flex', gap: 8, marginBottom: 16, overflowX: 'auto', paddingBottom: 2 }}>
          {(['konseling', 'pelanggaran', 'kasus', 'prestasi'] as HistTab[]).map(t => (
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
                background: histTab === t ? P : '#fff',
                color: histTab === t ? '#fff' : '#64748B',
                boxShadow: histTab === t ? `0 4px 14px rgba(79,70,229,0.4)` : '0 1px 4px rgba(0,0,0,0.06)',
                transition: 'all 0.2s',
                textTransform: 'capitalize',
              }}
            >
              {t}
            </button>
          ))}
        </div>

        {/* History list */}
        {histTab === 'konseling' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {konseling.length === 0 ? (
              <p style={{ textAlign: 'center', fontSize: 12, color: '#94A3B8', margin: '20px 0' }}>Tidak ada riwayat konseling</p>
            ) : konseling.map((item, i) => {
              const statusStr = item.status === 'pending' ? 'Menunggu' : item.status === 'approved' ? 'Disetujui' : item.status === 'completed' ? 'Selesai' : 'Batal';
              const sc = statusColor(statusStr)
              return (
                <div key={item.id || i} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 12 }}>
                    <div style={{ flex: 1, minWidth: 0 }}>
                      <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                        <div style={{ width: 8, height: 8, borderRadius: '50%', background: P, marginTop: 5, flexShrink: 0, animation: statusStr === 'Disetujui' ? 'pulseDot 1.5s ease-in-out infinite' : 'none' }} />
                        <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0, lineHeight: 1.4 }}>{item.note || item.type || 'Sesi Konseling'}</p>
                      </div>
                      <p style={{ fontSize: 11, color: '#94A3B8', margin: '6px 0 0 16px' }}>
                        {item.schedule_date} {item.schedule_time} {item.counselor_name ? `· ${item.counselor_name}` : ''}
                      </p>
                    </div>
                    <span style={{ flexShrink: 0, fontSize: 11, fontWeight: 800, padding: '4px 10px', borderRadius: 8, background: sc.bg, color: sc.c, fontFamily: 'Nunito' }}>{statusStr}</span>
                  </div>
                </div>
              )
            })}
          </div>
        )}

        {histTab === 'prestasi' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {prestasi.length === 0 ? (
              <p style={{ textAlign: 'center', fontSize: 12, color: '#94A3B8', margin: '20px 0' }}>Tidak ada riwayat prestasi</p>
            ) : prestasi.map((item, i) => (
              <div key={item.id || i} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                  <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${AM} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
                  </div>
                  <div>
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.achievement_name}</p>
                    <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 8px' }}>{item.date} · {item.level}</p>
                    <span style={{ fontSize: 11, fontWeight: 800, padding: '3px 8px', borderRadius: 8, background: '#FEF3C7', color: '#D97706' }}>+{item.point || 0} poin prestasi</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

        {(histTab === 'pelanggaran' || histTab === 'kasus') && (
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', paddingTop: 40, paddingBottom: 40, animation: 'fadeUp 0.3s ease both' }}>
            <div style={{ width: 64, height: 64, borderRadius: '50%', background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 12 }}>
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="1.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </div>
            <p style={{ fontWeight: 700, color: '#64748B', fontSize: 14, margin: 0, fontFamily: 'Nunito' }}>Tidak ada data</p>
            <p style={{ fontSize: 12, color: '#94A3B8', marginTop: 4 }}>Kamu tidak memiliki {histTab} apapun 🎉</p>
          </div>
        )}
      </div>
    </div>
  )
}

// ─── JADWAL SCREEN ────────────────────────────────────────────────────────────
