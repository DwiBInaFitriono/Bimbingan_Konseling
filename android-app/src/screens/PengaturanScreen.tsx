import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function PengaturanScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [notif, setNotif] = useState(true)
  const [dark, setDark] = useState(false)
  const [lang, setLang] = useState('id')

  const Toggle = ({ value, onChange }: { value: boolean; onChange: (v: boolean) => void }) => (
    <button
      onClick={() => onChange(!value)}
      style={{
        width: 48, height: 26, borderRadius: 13, background: value ? P : '#E2E8F0',
        border: 'none', cursor: 'pointer', position: 'relative', transition: 'background 0.3s', flexShrink: 0,
      }}
    >
      <div style={{ position: 'absolute', top: 3, left: value ? 25 : 3, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 4px rgba(0,0,0,0.25)', transition: 'left 0.3s' }} />
    </button>
  )

  return (
    <>
      <SubHeader title="Pengaturan Akun" sub="Atur preferensi akun Anda" onBack={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>

        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Informasi Profil</p>
            </div>
            {[
              { label: 'Nama Lengkap', value: 'Ahmad Rizky' },
              { label: 'Email', value: 'siswa@school.sch.id' },
              { label: 'NIS', value: '2024001' },
              { label: 'Kelas', value: 'X RPL 1 – Rekayasa Perangkat Lunak' },
            ].map((row, i, arr) => (
              <div key={i} style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', gap: 12, borderBottom: i < arr.length - 1 ? '1px solid #F8FAFC' : 'none' }}>
                <span style={{ fontSize: 13, color: '#64748B', fontWeight: 600 }}>{row.label}</span>
                <span style={{ fontSize: 13, color: '#1E293B', fontWeight: 700, textAlign: 'right', maxWidth: '60%', wordBreak: 'break-all' }}>{row.value}</span>
              </div>
            ))}
          </div>
        </FU>

        <FU d={80}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Preferensi</p>
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #F8FAFC' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Notifikasi Push</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Aktifkan pemberitahuan konseling</p>
              </div>
              <Toggle value={notif} onChange={setNotif} />
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #F8FAFC' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Mode Gelap</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Tampilan gelap untuk layar</p>
              </div>
              <Toggle value={dark} onChange={setDark} />
            </div>
            <div style={{ padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div>
                <p style={{ fontSize: 14, color: '#1E293B', fontWeight: 700, margin: 0, fontFamily: 'Nunito' }}>Bahasa</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: '2px 0 0' }}>Bahasa tampilan aplikasi</p>
              </div>
              <select value={lang} onChange={e => setLang(e.target.value)} style={{ fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: '1.5px solid #E2E8F0', borderRadius: 10, padding: '6px 10px', outline: 'none' }}>
                <option value="id">🇮🇩 Bahasa Indonesia</option>
                <option value="en">🇺🇸 English</option>
              </select>
            </div>
          </div>
        </FU>

        <FU d={160}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9' }}>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 13, color: '#475569', margin: 0, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Informasi Akun</p>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', padding: '16px' }}>
              {[
                { icon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2" strokeLinecap="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>, label: 'Tgl Daftar', value: '05 Agu 2026' },
                { icon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>, label: 'Diperbarui', value: '9 jam lalu' },
                { icon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke={AM} strokeWidth="2" strokeLinecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>, label: 'Status', value: 'Aktif' },
              ].map((s, i) => (
                <div key={i} style={{ textAlign: 'center', padding: '4px' }}>
                  <div style={{ display: 'flex', justifyContent: 'center', marginBottom: 6 }}>{s.icon}</div>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '0 0 3px' }}>{s.label}</p>
                  <p style={{ fontSize: 12, color: '#1E293B', fontWeight: 800, margin: 0, fontFamily: 'Nunito' }}>{s.value}</p>
                </div>
              ))}
            </div>
          </div>
        </FU>
      </div>
    </>
  )
}

// ─── BANTUAN SCREEN ───────────────────────────────────────────────────────────
