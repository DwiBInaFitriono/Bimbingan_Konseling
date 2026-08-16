import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';
import { ApiService } from '../services/api';


export function UbahPasswordScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [old, setOld] = useState('')
  const [neu, setNeu] = useState('')
  const [conf, setConf] = useState('')
  const [loading, setLoading] = useState(false)
  const [done, setDone] = useState(false)
  const [toast, setToast] = useState<{ msg: string; ok: boolean } | null>(null)

  const showToast = (msg: string, ok = false) => {
    setToast({ msg, ok })
    setTimeout(() => setToast(null), 4000)
  }

  const strength = neu.length === 0 ? 0 : neu.length < 6 ? 1 : neu.length < 10 ? 2 : 3
  const strengthLabel = ['', 'Lemah', 'Sedang', 'Kuat']
  const strengthColor = ['', RD, AM, T]

  const PasswordInput = ({ label, value, onChange, placeholder }: { label: string; value: string; onChange: (v: string) => void; placeholder: string }) => {
    const [show, setShow] = useState(false)
    const [focused, setFocused] = useState(false)
    return (
      <div>
        <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.04em' }}>{label} <span style={{ color: RD }}>*</span></label>
        <div style={{ position: 'relative' }}>
          <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: focused ? V : '#94A3B8', transition: 'color 0.2s' }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          </div>
          <input
            type={show ? 'text' : 'password'}
            value={value}
            onChange={e => onChange(e.target.value)}
            placeholder={placeholder}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
            style={{ width: '100%', paddingLeft: 42, paddingRight: 44, paddingTop: 13, paddingBottom: 13, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${focused ? V : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
          />
          <button
            onClick={() => setShow(s => !s)}
            style={{ position: 'absolute', right: 14, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', padding: 0, display: 'flex' }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
              {show ? <><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/></> : <><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></>}
            </svg>
          </button>
        </div>
      </div>
    )
  }

  const handleUbah = async () => {
    if (!old || !neu || neu !== conf || neu.length < 6) return;
    setLoading(true);
    try {
      const dataStr = localStorage.getItem('student_data');
      if (!dataStr) {
        showToast('Sesi login tidak ditemukan. Silakan login kembali.');
        setLoading(false);
        return;
      }
      const student = JSON.parse(dataStr);
      const res = await ApiService.ubahPassword(student.id, old, neu);
      if (res.success) {
        setDone(true);
        setTimeout(() => navigate('profil'), 2500);
      } else {
        showToast(res.message || 'Gagal mengubah password');
      }
    } catch (err: any) {
      showToast(err.message || 'Terjadi kesalahan');
    } finally {
      setLoading(false);
    }
  }

  if (done) {
    return (
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#F1F5F9', padding: '0 32px', animation: 'bounceIn 0.5s ease both' }}>
        <div style={{ width: 80, height: 80, borderRadius: '50%', background: `linear-gradient(135deg, ${V} 0%, ${P} 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20, boxShadow: `0 12px 32px rgba(124,58,237,0.45)` }}>
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 20, color: '#1E293B', textAlign: 'center', margin: '0 0 8px' }}>Password Diubah!</h2>
        <p style={{ color: '#64748B', fontSize: 13, textAlign: 'center' }}>Password akun Anda berhasil diperbarui. Gunakan password baru untuk login berikutnya.</p>
      </div>
    )
  }

  return (
    <>
      {/* Toast Notification */}
      {toast && (
        <div style={{ position: 'fixed', top: 16, left: '50%', transform: 'translateX(-50%)', zIndex: 9999, maxWidth: 320, width: 'calc(100% - 32px)', padding: '12px 16px', borderRadius: 14, background: toast.ok ? '#ECFDF5' : '#FFF1F2', border: `1.5px solid ${toast.ok ? '#6EE7B7' : '#FCA5A5'}`, boxShadow: '0 8px 24px rgba(0,0,0,0.12)', display: 'flex', alignItems: 'center', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
          <div style={{ width: 28, height: 28, borderRadius: 8, background: toast.ok ? '#10B981' : '#EF4444', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
              {toast.ok ? <path d="M20 6L9 17l-5-5"/> : <><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></>}
            </svg>
          </div>
          <p style={{ margin: 0, fontSize: 13, color: toast.ok ? '#065F46' : '#9B1C1C', fontFamily: 'Inter', lineHeight: 1.5 }}>{toast.msg}</p>
        </div>
      )}
      <SubHeader title="Ubah Password" sub="Keamanan & privasi akun" onBack={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 20, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
              <PasswordInput label="Password Saat Ini" value={old} onChange={setOld} placeholder="Masukkan password saat ini" />
              <PasswordInput label="Password Baru" value={neu} onChange={setNeu} placeholder="Min. 6 karakter" />
              {neu.length > 0 && (
                <div>
                  <div style={{ display: 'flex', gap: 4, marginBottom: 4 }}>
                    {[1, 2, 3].map(i => (
                      <div key={i} style={{ flex: 1, height: 4, borderRadius: 4, background: i <= strength ? strengthColor[strength] : '#E2E8F0', transition: 'all 0.3s' }} />
                    ))}
                  </div>
                  <p style={{ fontSize: 11, color: strengthColor[strength], fontWeight: 700, margin: 0 }}>Kekuatan: {strengthLabel[strength]}</p>
                </div>
              )}
              <PasswordInput label="Konfirmasi Password" value={conf} onChange={setConf} placeholder="Ulangi password baru" />
              {conf.length > 0 && neu !== conf && (
                <p style={{ fontSize: 12, color: RD, fontWeight: 600, margin: '-8px 0 0', display: 'flex', alignItems: 'center', gap: 6 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  Password tidak cocok
                </p>
              )}
            </div>
          </div>
        </FU>

        <FU d={100}>
          <div style={{ background: '#FFF5F5', border: '1px solid #FED7D7', borderRadius: 16, padding: '14px 16px', display: 'flex', gap: 12, alignItems: 'flex-start' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2" strokeLinecap="round" style={{ marginTop: 1, flexShrink: 0 }}><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <p style={{ fontSize: 12, color: '#9B1C1C', margin: 0, lineHeight: 1.6 }}>Jangan bagikan password kepada siapapun. Password yang kuat menggunakan kombinasi huruf, angka, dan simbol.</p>
          </div>
        </FU>

        <FU d={150}>
          <button
            onClick={handleUbah}
            disabled={loading || !old || !neu || neu !== conf || neu.length < 6}
            style={{
              width: '100%', padding: '16px', borderRadius: 18, border: 'none',
              cursor: (loading || !old || !neu || neu !== conf || neu.length < 6) ? 'not-allowed' : 'pointer',
              background: (loading || !old || !neu || neu !== conf || neu.length < 6) ? '#CBD5E1' : `linear-gradient(135deg, ${V} 0%, ${P} 100%)`,
              boxShadow: (!loading && old && neu && neu === conf && neu.length >= 6) ? `0 10px 28px rgba(124,58,237,0.4)` : 'none',
              color: '#fff', fontWeight: 900, fontSize: 16, fontFamily: 'Nunito',
              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, transition: 'all 0.25s',
            }}
          >
            {loading ? (
              <><svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg> Memproses...</>
            ) : (
              <><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg> Ubah Password</>
            )}
          </button>
        </FU>
      </div>
    </>
  )
}

// ─── PENGATURAN SCREEN ────────────────────────────────────────────────────────
