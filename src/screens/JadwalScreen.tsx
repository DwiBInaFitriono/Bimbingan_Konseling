import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function JadwalScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [tipe, setTipe] = useState<'individu' | 'kelompok'>('individu')
  const [tanggal, setTanggal] = useState('')
  const [guru, setGuru] = useState('')
  const [sesi, setSesi] = useState('')
  const [topik, setTopik] = useState('')
  const [desc, setDesc] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [submitted, setSubmitted] = useState(false)

  const sesiOptions = ['07:00 – 08:00', '08:00 – 09:00', '09:00 – 10:00', '10:00 – 11:00', '13:00 – 14:00']

  const handleSubmit = () => {
    if (!tanggal || !guru || !sesi || !topik) return
    setSubmitting(true)
    setTimeout(() => {
      setSubmitting(false)
      setSubmitted(true)
      setTimeout(() => { navigate('dashboard'); setSubmitted(false) }, 2200)
    }, 1800)
  }

  if (submitted) {
    return (
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#F1F5F9', padding: '0 32px', animation: 'bounceIn 0.55s ease both' }}>
        <div style={{ width: 88, height: 88, borderRadius: '50%', background: `linear-gradient(135deg, ${T} 0%, #059669 100%)`, boxShadow: `0 16px 40px rgba(16,185,129,0.5)`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20 }}>
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round" style={{ animation: 'fadeIn 0.4s ease 0.3s both' }}>
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </div>
        <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 22, color: '#1E293B', textAlign: 'center', margin: '0 0 8px' }}>Pengajuan Terkirim!</h2>
        <p style={{ color: '#64748B', fontSize: 14, textAlign: 'center', margin: 0, lineHeight: 1.6 }}>Jadwal konseling Anda sedang diproses oleh guru BK. Kami akan memberi tahu Anda segera.</p>
        <div style={{ marginTop: 24, display: 'flex', gap: 4 }}>
          {[0, 1, 2].map(i => (
            <div key={i} style={{ width: 8, height: 8, borderRadius: '50%', background: T, animation: `pulseDot 1.2s ease-in-out ${i * 200}ms infinite` }} />
          ))}
        </div>
      </div>
    )
  }

  return (
    <>
      <SubHeader title="Pengajuan Jadwal" sub="Konseling BK · Rahasia dijamin" onBack={() => navigate('dashboard')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: '16px 16px 24px', display: 'flex', flexDirection: 'column', gap: 14 }}>

        {/* Section: Jadwal Pertemuan */}
        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Jadwal Pertemuan</span>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>
                  Tanggal Pertemuan <span style={{ color: RD }}>*</span>
                </label>
                <input
                  type="date"
                  value={tanggal}
                  onChange={e => setTanggal(e.target.value)}
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${tanggal ? P : '#E2E8F0'}`, outline: 'none', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
                <div>
                  <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Guru BK <span style={{ color: RD }}>*</span></label>
                  <select
                    value={guru}
                    onChange={e => { setGuru(e.target.value); setSesi('') }}
                    style={{ width: '100%', padding: '12px 10px', borderRadius: 12, fontSize: 13, color: guru ? '#1E293B' : '#94A3B8', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${guru ? P : '#E2E8F0'}`, outline: 'none', appearance: 'none', boxSizing: 'border-box' }}
                  >
                    <option value="">-- Pilih Guru --</option>
                    <option value="rio">Rio S.Pd</option>
                    <option value="ani">Ani M.Pd</option>
                    <option value="budi">Budi S.Pd</option>
                  </select>
                </div>
                <div>
                  <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Sesi Waktu <span style={{ color: RD }}>*</span></label>
                  <select
                    value={sesi}
                    onChange={e => setSesi(e.target.value)}
                    disabled={!guru}
                    style={{ width: '100%', padding: '12px 10px', borderRadius: 12, fontSize: 13, color: sesi ? '#1E293B' : '#94A3B8', fontFamily: 'Inter', background: guru ? '#F8FAFC' : '#F1F5F9', border: `1.5px solid ${sesi ? P : '#E2E8F0'}`, outline: 'none', appearance: 'none', boxSizing: 'border-box', cursor: guru ? 'auto' : 'not-allowed' }}
                  >
                    <option value="">{guru ? 'Pilih sesi' : 'Pilih guru dulu'}</option>
                    {guru && sesiOptions.map(s => <option key={s} value={s}>{s}</option>)}
                  </select>
                </div>
              </div>
            </div>
          </div>
        </FU>

        {/* Section: Tipe Konseling */}
        <FU d={80}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><circle cx="17" cy="21" r="1"/><circle cx="9" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57L23 6H6"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Tipe Konseling</span>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
              {([
                { id: 'individu', label: 'Individu', sub: 'Sesi pribadi 1 lawan 1', icon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> },
                { id: 'kelompok', label: 'Kelompok', sub: 'Ajak teman bergabung', icon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="9" cy="7" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M1 20c0-3.3 3.1-6 7-6M13 18c0-2.8 2.7-5 6-5"/></svg> },
              ] as { id: string; label: string; sub: string; icon: React.ReactNode }[]).map(({ id, label, sub, icon }) => (
                <button
                  key={id}
                  onClick={() => setTipe(id as 'individu' | 'kelompok')}
                  style={{
                    padding: '14px 12px',
                    borderRadius: 14,
                    border: `2px solid ${tipe === id ? P : '#E2E8F0'}`,
                    background: tipe === id ? '#EEF2FF' : '#F8FAFC',
                    cursor: 'pointer',
                    textAlign: 'left',
                    transition: 'all 0.2s',
                  }}
                >
                  <div style={{ color: tipe === id ? P : '#CBD5E1', marginBottom: 8 }}>{icon}</div>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: tipe === id ? P : '#475569', margin: '0 0 3px' }}>{label}</p>
                  <p style={{ fontSize: 11, color: tipe === id ? IND : '#94A3B8', margin: 0, lineHeight: 1.4 }}>{sub}</p>
                </button>
              ))}
            </div>
          </div>
        </FU>

        {/* Section: Detail Konsultasi */}
        <FU d={160}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Detail Konsultasi</span>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Topik Bahasan <span style={{ color: RD }}>*</span></label>
                <input
                  type="text"
                  value={topik}
                  onChange={e => setTopik(e.target.value)}
                  placeholder="Contoh: Kendala belajar, konsultasi karir..."
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${topik ? P : '#E2E8F0'}`, outline: 'none', boxSizing: 'border-box' }}
                  onFocus={e => e.target.style.borderColor = P}
                  onBlur={e => e.target.style.borderColor = topik ? P : '#E2E8F0'}
                />
              </div>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Deskripsi Singkat <span style={{ color: '#CBD5E1' }}>(opsional)</span></label>
                <textarea
                  value={desc}
                  onChange={e => setDesc(e.target.value)}
                  placeholder="Ceritakan singkat apa yang ingin didiskusikan... (Rahasia dijamin 🔒)"
                  rows={4}
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: '1.5px solid #E2E8F0', outline: 'none', resize: 'none', boxSizing: 'border-box', lineHeight: 1.6 }}
                  onFocus={e => e.target.style.borderColor = P}
                  onBlur={e => e.target.style.borderColor = '#E2E8F0'}
                />
                <p style={{ textAlign: 'right', fontSize: 11, color: '#CBD5E1', margin: '4px 0 0' }}>{desc.length}/500</p>
              </div>
            </div>
          </div>
        </FU>

        {/* Submit button */}
        <FU d={220}>
          <button
            onClick={handleSubmit}
            disabled={submitting || !tanggal || !guru || !sesi || !topik}
            style={{
              width: '100%',
              padding: '16px',
              borderRadius: 18,
              border: 'none',
              cursor: (submitting || !tanggal || !guru || !sesi || !topik) ? 'not-allowed' : 'pointer',
              background: (submitting || !tanggal || !guru || !sesi || !topik) ? '#CBD5E1' : `linear-gradient(135deg, ${P} 0%, ${V} 100%)`,
              boxShadow: (!submitting && tanggal && guru && sesi && topik) ? `0 10px 28px rgba(79,70,229,0.4)` : 'none',
              color: '#fff',
              fontWeight: 900,
              fontSize: 16,
              fontFamily: 'Nunito',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              transition: 'all 0.25s',
            }}
          >
            {submitting ? (
              <>
                <svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg>
                Mengirim Pengajuan...
              </>
            ) : (
              <>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                Kirim Pengajuan
              </>
            )}
          </button>
        </FU>
      </div>
    </>
  )
}

// ─── RIWAYAT SCREEN ───────────────────────────────────────────────────────────
