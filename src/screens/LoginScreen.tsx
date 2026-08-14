import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';
import { InputField } from '../components/InputField';

export function LoginScreen({ onLogin }: { onLogin: () => void }) {
  const [nis, setNis] = useState('')
  const [pass, setPass] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [nisFocused, setNisFocused] = useState(false)
  const [passFocused, setPassFocused] = useState(false)

  const handleLogin = () => {
    if (!nis || !pass) { setError('Lengkapi semua field terlebih dahulu'); return }
    setError('')
    setLoading(true)
    setTimeout(() => { setLoading(false); onLogin() }, 1800)
  }

  return (
    <div style={{ flex: 1, display: 'flex', flexDirection: 'column', background: `linear-gradient(145deg, #5B21B6 0%, ${P} 45%, ${IND} 75%, ${V} 100%)`, position: 'relative', overflow: 'hidden' }}>
      {/* Animated blobs */}
      <div style={{ position: 'absolute', inset: 0, pointerEvents: 'none', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: '8%', left: '-12%', width: 220, height: 220, background: 'rgba(255,255,255,0.07)', borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%', animation: 'blob 9s ease-in-out infinite, float 7s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '18%', right: '-8%', width: 160, height: 160, background: 'rgba(255,255,255,0.09)', borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%', animation: 'blob 11s ease-in-out infinite reverse, float2 8s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '40%', left: '8%', width: 70, height: 70, background: `rgba(16,185,129,0.25)`, borderRadius: '50%', animation: 'float3 5.5s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '30%', right: '12%', width: 44, height: 44, background: `rgba(245,158,11,0.25)`, borderRadius: '50%', animation: 'float2 4.5s ease-in-out infinite 1s' }} />
        <div style={{ position: 'absolute', top: '55%', left: '18%', width: 28, height: 28, background: 'rgba(255,255,255,0.18)', borderRadius: '50%', animation: 'float 3.8s ease-in-out infinite 0.8s' }} />
        <div style={{ position: 'absolute', top: '12%', left: '40%', width: 18, height: 18, background: 'rgba(255,255,255,0.15)', borderRadius: '50%', animation: 'float2 3s ease-in-out infinite 2s' }} />
        {/* Ring decorations */}
        <div style={{ position: 'absolute', top: -90, right: -90, width: 300, height: 300, border: '1px solid rgba(255,255,255,0.08)', borderRadius: '50%' }} />
        <div style={{ position: 'absolute', top: -50, right: -50, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.06)', borderRadius: '50%' }} />
      </div>

      {/* Top hero */}
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '0 32px', position: 'relative', zIndex: 1 }}>
        <FU d={0}>
          <div style={{ position: 'relative', marginBottom: 16, display: 'flex', justifyContent: 'center' }}>
            <div
              style={{ width: 84, height: 84, borderRadius: 24, background: 'rgba(255,255,255,0.15)', backdropFilter: 'blur(12px)', border: '1.5px solid rgba(255,255,255,0.25)', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 12px 40px rgba(0,0,0,0.25)', animation: 'rippleOut 2.5s ease-in-out infinite' }}
            >
              <span style={{ fontSize: 30, fontWeight: 900, color: '#fff', fontFamily: 'Nunito', letterSpacing: -1 }}>BK</span>
            </div>
          </div>
        </FU>
        <FU d={80}>
          <div style={{ textAlign: 'center' }}>
            <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 26, fontFamily: 'Nunito', margin: 0, letterSpacing: -0.5 }}>SistemBK</h1>
            <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 13, margin: '6px 0 0', fontWeight: 500 }}>SMK Negeri 1 Contoh Kota</p>
          </div>
        </FU>
      </div>

      {/* Form card */}
      <FU d={160}>
        <div
          style={{ background: '#fff', borderRadius: '32px 32px 0 0', padding: '28px 24px 24px', position: 'relative', boxShadow: '0 -24px 60px rgba(0,0,0,0.18)', zIndex: 1 }}
        >
          <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 22, color: '#1E293B', margin: '0 0 4px' }}>Selamat Datang 👋</h2>
          <p style={{ color: '#94A3B8', fontSize: 13, margin: '0 0 20px', fontWeight: 500 }}>Masuk untuk mengakses akun siswa Anda</p>

          {error && (
            <div style={{ background: '#FEF2F2', border: '1px solid #FECACA', borderRadius: 12, padding: '10px 14px', marginBottom: 16, fontSize: 13, color: RD, fontWeight: 600, display: 'flex', alignItems: 'center', gap: 8 }}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {error}
            </div>
          )}

          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            <div>
              <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                NIS / Username <span style={{ color: RD }}>*</span>
              </label>
              <div style={{ position: 'relative' }}>
                <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: nisFocused ? P : '#94A3B8', transition: 'color 0.2s' }}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
                <input
                  type="text"
                  value={nis}
                  onChange={e => setNis(e.target.value)}
                  placeholder="Masukkan NIS Anda"
                  onFocus={() => setNisFocused(true)}
                  onBlur={() => setNisFocused(false)}
                  style={{ width: '100%', paddingLeft: 42, paddingRight: 16, paddingTop: 14, paddingBottom: 14, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${nisFocused ? P : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
                />
              </div>
            </div>

            <div>
              <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                Password <span style={{ color: RD }}>*</span>
              </label>
              <div style={{ position: 'relative' }}>
                <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: passFocused ? P : '#94A3B8', transition: 'color 0.2s' }}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                <input
                  type={showPass ? 'text' : 'password'}
                  value={pass}
                  onChange={e => setPass(e.target.value)}
                  placeholder="Masukkan password"
                  onFocus={() => setPassFocused(true)}
                  onBlur={() => setPassFocused(false)}
                  style={{ width: '100%', paddingLeft: 42, paddingRight: 44, paddingTop: 14, paddingBottom: 14, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${passFocused ? P : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
                />
                <button
                  onClick={() => setShowPass(v => !v)}
                  style={{ position: 'absolute', right: 14, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', padding: 0, display: 'flex' }}
                >
                  {showPass
                    ? <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/></svg>
                    : <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  }
                </button>
              </div>
            </div>
          </div>

          <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: 8, marginBottom: 20 }}>
            <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: P, fontSize: 13, fontWeight: 700, fontFamily: 'Nunito' }}>
              Lupa Password?
            </button>
          </div>

          <button
            onClick={handleLogin}
            disabled={loading}
            style={{
              width: '100%',
              padding: '16px',
              borderRadius: 18,
              border: 'none',
              cursor: loading ? 'not-allowed' : 'pointer',
              background: loading ? '#94A3B8' : `linear-gradient(135deg, ${P} 0%, ${V} 100%)`,
              boxShadow: loading ? 'none' : `0 10px 28px rgba(79,70,229,0.45)`,
              color: '#fff',
              fontWeight: 900,
              fontSize: 16,
              fontFamily: 'Nunito',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              transition: 'all 0.25s',
              transform: 'scale(1)',
            }}
            onMouseDown={e => { if (!loading) (e.currentTarget as HTMLButtonElement).style.transform = 'scale(0.97)' }}
            onMouseUp={e => (e.currentTarget as HTMLButtonElement).style.transform = 'scale(1)'}
          >
            {loading ? (
              <>
                <svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg>
                Memproses...
              </>
            ) : (
              <>
                Masuk Sekarang
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </>
            )}
          </button>

          <div style={{ display: 'flex', alignItems: 'center', gap: 10, margin: '16px 0 0' }}>
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
            <span style={{ fontSize: 12, color: '#CBD5E1', fontWeight: 600, whiteSpace: 'nowrap' }}>atau coba tanpa akun</span>
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
          </div>

          <button
            onClick={onLogin}
            style={{
              width: '100%',
              marginTop: 12,
              padding: '13px',
              borderRadius: 18,
              border: `1.5px dashed ${P}`,
              cursor: 'pointer',
              background: '#F8FAFF',
              color: P,
              fontWeight: 800,
              fontSize: 14,
              fontFamily: 'Nunito',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              transition: 'all 0.2s',
            }}
            onMouseDown={e => (e.currentTarget.style.background = '#EEF2FF')}
            onMouseUp={e => (e.currentTarget.style.background = '#F8FAFF')}
            onMouseLeave={e => (e.currentTarget.style.background = '#F8FAFF')}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
              <polygon points="5 3 19 12 5 21 5 3" fill={P} stroke={P}/>
            </svg>
            Masuk sebagai Demo
          </button>

          <p style={{ textAlign: 'center', fontSize: 11, color: '#CBD5E1', marginTop: 10, marginBottom: 0, fontWeight: 500 }}>
            Butuh bantuan? Hubungi admin sekolah
          </p>

          <div style={{ display: 'flex', alignItems: 'center', gap: 10, margin: '14px 0 2px' }}>
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
            <span style={{ fontSize: 11, color: '#CBD5E1', fontWeight: 600, whiteSpace: 'nowrap' }}>Flutter Source Code</span>
            <div style={{ flex: 1, height: 1, background: '#F1F5F9' }} />
          </div>

          <a
            href={window.location.origin + '/flutter_sistembk.zip'}
            download="flutter_sistembk.zip"
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              width: '100%',
              marginTop: 8,
              padding: '11px',
              borderRadius: 14,
              border: '1.5px solid #E2E8F0',
              background: '#FAFAFA',
              color: '#475569',
              fontWeight: 700,
              fontSize: 13,
              fontFamily: 'Nunito',
              textDecoration: 'none',
              transition: 'all 0.2s',
            }}
            onMouseEnter={e => { (e.currentTarget as HTMLAnchorElement).style.background = '#F1F5F9'; (e.currentTarget as HTMLAnchorElement).style.borderColor = '#CBD5E1' }}
            onMouseLeave={e => { (e.currentTarget as HTMLAnchorElement).style.background = '#FAFAFA'; (e.currentTarget as HTMLAnchorElement).style.borderColor = '#E2E8F0' }}
          >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
              <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Download Flutter ZIP (32 KB)
          </a>
        </div>
      </FU>
    </div>
  )
}

// ─── DASHBOARD SCREEN ─────────────────────────────────────────────────────────
