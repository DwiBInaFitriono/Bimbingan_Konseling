import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';
import { InputField } from '../components/InputField';


export function EditProfilScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [name, setName] = useState('Ahmad Rizky')
  const [nis, setNis] = useState('2024001')
  const [email, setEmail] = useState('siswa@school.sch.id')
  const [saved, setSaved] = useState(false)

  return (
    <>
      <SubHeader title="Edit Profil" sub="Kelola informasi profil Anda" onBack={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 20, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: 24 }}>
              <div style={{ position: 'relative', marginBottom: 10 }}>
                <div style={{ width: 88, height: 88, borderRadius: '50%', background: `linear-gradient(135deg, ${P} 0%, ${V} 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, fontWeight: 900, color: '#fff', fontFamily: 'Nunito' }}>
                  AR
                </div>
                <button style={{ position: 'absolute', bottom: -2, right: -2, width: 30, height: 30, borderRadius: '50%', background: P, border: 'none', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: `0 4px 14px rgba(79,70,229,0.5)` }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </button>
              </div>
              <p style={{ fontSize: 12, color: '#94A3B8', margin: 0 }}>JPG, JPEG atau PNG · Maks 800KB</p>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
              <InputField
                label="Nama Lengkap" required value={name} onChange={setName} placeholder="Masukkan nama lengkap"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>}
              />
              <InputField label="NIS" value={nis} onChange={setNis} placeholder="Masukkan NIS" />
              <InputField
                label="Alamat Email" value={email} onChange={setEmail} placeholder="email@school.sch.id"
                icon={<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>}
              />
            </div>
            <p style={{ fontSize: 11, color: '#CBD5E1', marginTop: 16, marginBottom: 0 }}>Terakhir diperbarui: 05 Agu 2026, 06:04</p>
          </div>
        </FU>
        <FU d={100}>
          <button
            onClick={() => { setSaved(true); setTimeout(() => setSaved(false), 2000) }}
            style={{
              width: '100%', padding: '16px', borderRadius: 18, border: 'none', cursor: 'pointer',
              background: saved ? `linear-gradient(135deg, ${T} 0%, #059669 100%)` : `linear-gradient(135deg, ${P} 0%, ${V} 100%)`,
              boxShadow: saved ? `0 10px 28px rgba(16,185,129,0.4)` : `0 10px 28px rgba(79,70,229,0.4)`,
              color: '#fff', fontWeight: 900, fontSize: 16, fontFamily: 'Nunito',
              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, transition: 'all 0.3s',
            }}
          >
            {saved
              ? <><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M20 6L9 17l-5-5"/></svg> Tersimpan!</>
              : <><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg> Simpan Perubahan</>
            }
          </button>
        </FU>
      </div>
    </>
  )
}

// ─── UBAH PASSWORD SCREEN ─────────────────────────────────────────────────────
