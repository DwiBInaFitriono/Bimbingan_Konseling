import { useState, useEffect } from 'react'

type Screen =
  | 'login'
  | 'dashboard'
  | 'jadwal'
  | 'riwayat'
  | 'profil'
  | 'editprofil'
  | 'ubahpassword'
  | 'pengaturan'
  | 'bantuan'

type NavTab = 'beranda' | 'jadwal' | 'riwayat' | 'profil'
type HistTab = 'konseling' | 'pelanggaran' | 'kasus' | 'prestasi'

const P   = '#4F46E5'
const PD  = '#4338CA'
const V   = '#7C3AED'
const T   = '#10B981'
const AM  = '#F59E0B'
const RD  = '#EF4444'
const IND = '#6366F1'

// ─── Utility ─────────────────────────────────────────────────────────────────

function FU({ children, d = 0, cls = '' }: { children: React.ReactNode; d?: number; cls?: string }) {
  return (
    <div className={cls} style={{ animation: `fadeUp 0.42s ease ${d}ms both` }}>
      {children}
    </div>
  )
}

function SIR({ children, k }: { children: React.ReactNode; k: number }) {
  return (
    <div
      key={k}
      style={{ animation: 'slideInRight 0.38s cubic-bezier(0.22,1,0.36,1) both', flex: 1, minHeight: 0, display: 'flex', flexDirection: 'column', overflow: 'hidden' }}
    >
      {children}
    </div>
  )
}

// ─── Status Bar ───────────────────────────────────────────────────────────────

function StatusBar({ dark }: { dark: boolean }) {
  const [time, setTime] = useState(() => {
    const n = new Date()
    return `${String(n.getHours()).padStart(2, '0')}:${String(n.getMinutes()).padStart(2, '0')}`
  })
  useEffect(() => {
    const id = setInterval(() => {
      const n = new Date()
      setTime(`${String(n.getHours()).padStart(2, '0')}:${String(n.getMinutes()).padStart(2, '0')}`)
    }, 30000)
    return () => clearInterval(id)
  }, [])
  const c = dark ? '#fff' : '#1e293b'
  return (
    <div
      className="flex justify-between items-center px-6 flex-shrink-0"
      style={{ height: 44, color: c, fontFamily: 'Nunito' }}
    >
      <span style={{ fontSize: 14, fontWeight: 800 }}>{time}</span>
      <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
        <div style={{ display: 'flex', alignItems: 'flex-end', gap: 2 }}>
          {[4, 6, 8, 11].map((h, i) => (
            <div key={i} style={{ width: 3, height: h, background: c, borderRadius: 1, opacity: i < 3 ? 1 : 0.4 }} />
          ))}
        </div>
        <svg width="16" height="12" viewBox="0 0 22 16" fill="none">
          <path d="M1 4.5C4.3 1.5 7.9 0 11 0s6.7 1.5 10 4.5" stroke={c} strokeWidth="2" strokeLinecap="round"/>
          <path d="M3.5 7.5C6 5 8.7 4 11 4s5 1 7.5 3.5" stroke={c} strokeWidth="2" strokeLinecap="round"/>
          <path d="M6.5 10.5C8 9 9.5 8.5 11 8.5s3 .5 4.5 2" stroke={c} strokeWidth="2" strokeLinecap="round"/>
          <circle cx="11" cy="15" r="1.5" fill={c}/>
        </svg>
        <div style={{ display: 'flex', alignItems: 'center' }}>
          <div style={{ width: 22, height: 11, border: `1.5px solid ${c}`, borderRadius: 3, padding: '1.5px', display: 'flex', alignItems: 'center' }}>
            <div style={{ width: '75%', height: '100%', background: T, borderRadius: 1.5 }} />
          </div>
          <div style={{ width: 2, height: 6, background: c, borderRadius: '0 2px 2px 0', marginLeft: 1 }} />
        </div>
      </div>
    </div>
  )
}

// ─── Bottom Nav ───────────────────────────────────────────────────────────────

function BottomNav({ tab, onTab }: { tab: NavTab; onTab: (t: NavTab) => void }) {
  const items: { id: NavTab; label: string }[] = [
    { id: 'beranda', label: 'Beranda' },
    { id: 'jadwal', label: 'Jadwal' },
    { id: 'riwayat', label: 'Riwayat' },
    { id: 'profil', label: 'Profil' },
  ]
  return (
    <div
      className="flex-shrink-0 flex items-stretch"
      style={{ height: 64, background: '#fff', borderTop: '1px solid #F1F5F9', paddingBottom: 6 }}
    >
      {items.map(({ id, label }) => {
        const active = tab === id
        const c = active ? P : '#94A3B8'
        return (
          <button
            key={id}
            onClick={() => onTab(id)}
            className="flex-1 flex flex-col items-center justify-center gap-1 relative"
            style={{ border: 'none', background: 'transparent', cursor: 'pointer', transition: 'all 0.2s' }}
          >
            {active && (
              <div
                style={{ position: 'absolute', top: 0, left: '50%', transform: 'translateX(-50%)', width: 28, height: 3, background: P, borderRadius: '0 0 4px 4px' }}
              />
            )}
            <NavIcon id={id} c={c} active={active} />
            <span style={{ fontSize: 10, fontWeight: 700, color: c, fontFamily: 'Nunito', lineHeight: 1 }}>{label}</span>
          </button>
        )
      })}
    </div>
  )
}

function NavIcon({ id, c, active }: { id: string; c: string; active: boolean }) {
  const s = 22
  switch (id) {
    case 'beranda':
      return (
        <svg width={s} height={s} viewBox="0 0 24 24" fill={active ? c : 'none'} stroke={c} strokeWidth={active ? 0 : 2} strokeLinecap="round" strokeLinejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" fill={active ? c : 'none'} stroke={c} strokeWidth={active ? 0 : 2} />
          <polyline points="9 22 9 12 15 12 15 22" stroke={active ? '#fff' : c} strokeWidth="2" fill="none" />
        </svg>
      )
    case 'jadwal':
      return (
        <svg width={s} height={s} viewBox="0 0 24 24" fill={active ? c : 'none'} stroke={c} strokeWidth={1.8} strokeLinecap="round">
          <rect x="3" y="4" width="18" height="18" rx="3" />
          <path d="M16 2v4M8 2v4M3 10h18" stroke={active ? '#fff' : c} />
          <circle cx="8" cy="15" r="1.2" fill={active ? '#fff' : c} />
          <circle cx="12" cy="15" r="1.2" fill={active ? '#fff' : c} />
          <circle cx="16" cy="15" r="1.2" fill={active ? '#fff' : c} />
        </svg>
      )
    case 'riwayat':
      return (
        <svg width={s} height={s} viewBox="0 0 24 24" fill={active ? c : 'none'} stroke={c} strokeWidth={1.8} strokeLinecap="round">
          <circle cx="12" cy="12" r="10" />
          <polyline points="12 6 12 12 16 14" stroke={active ? '#fff' : c} />
        </svg>
      )
    case 'profil':
      return (
        <svg width={s} height={s} viewBox="0 0 24 24" fill={active ? c : 'none'} stroke={c} strokeWidth={1.8} strokeLinecap="round">
          <circle cx="12" cy="8" r="4" />
          <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
        </svg>
      )
    default: return null
  }
}

// ─── Screen Header (sub-screens) ─────────────────────────────────────────────

function SubHeader({ title, sub, onBack }: { title: string; sub: string; onBack: () => void }) {
  return (
    <div
      className="flex-shrink-0 px-4 pb-4"
      style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 60%, ${IND} 100%)`, paddingTop: 4, position: 'relative', overflow: 'hidden' }}
    >
      <div style={{ position: 'absolute', top: -40, right: -40, width: 160, height: 160, border: '1px solid rgba(255,255,255,0.08)', borderRadius: '50%', pointerEvents: 'none' }} />
      <div style={{ position: 'absolute', top: 10, right: 20, width: 80, height: 80, border: '1px solid rgba(255,255,255,0.06)', borderRadius: '50%', pointerEvents: 'none' }} />
      <div className="flex items-center gap-3">
        <button
          onClick={onBack}
          style={{ width: 36, height: 36, borderRadius: 12, background: 'rgba(255,255,255,0.15)', border: 'none', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer', flexShrink: 0 }}
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 18, fontFamily: 'Nunito', margin: 0, lineHeight: 1.2 }}>{title}</h1>
          <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, margin: 0, marginTop: 2 }}>{sub}</p>
        </div>
      </div>
    </div>
  )
}

// ─── Input Field ─────────────────────────────────────────────────────────────

function InputField({
  label, required = false, value, onChange, placeholder, type = 'text', icon,
}: {
  label: string; required?: boolean; value: string; onChange: (v: string) => void
  placeholder: string; type?: string; icon?: React.ReactNode
}) {
  const [focused, setFocused] = useState(false)
  return (
    <div>
      <label style={{ fontSize: 12, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.04em' }}>
        {label} {required && <span style={{ color: RD }}>*</span>}
      </label>
      <div style={{ position: 'relative' }}>
        {icon && <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: focused ? P : '#94A3B8', transition: 'color 0.2s' }}>{icon}</div>}
        <input
          type={type}
          value={value}
          onChange={e => onChange(e.target.value)}
          placeholder={placeholder}
          onFocus={() => setFocused(true)}
          onBlur={() => setFocused(false)}
          style={{
            width: '100%',
            paddingLeft: icon ? 42 : 16,
            paddingRight: 16,
            paddingTop: 13,
            paddingBottom: 13,
            borderRadius: 14,
            fontSize: 14,
            color: '#1E293B',
            fontFamily: 'Inter',
            background: '#F8FAFC',
            border: `1.5px solid ${focused ? P : '#E2E8F0'}`,
            outline: 'none',
            transition: 'border-color 0.2s',
            boxSizing: 'border-box',
          }}
        />
      </div>
    </div>
  )
}

// ─── LOGIN SCREEN ─────────────────────────────────────────────────────────────

function LoginScreen({ onLogin }: { onLogin: () => void }) {
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

function DashboardScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [histTab, setHistTab] = useState<HistTab>('konseling')

  const konseling = [
    { id: 1, title: 'Konsultasi Perencanaan Karir & Kuliah RPL', date: '07 Agu 2026', guru: 'Rio S.Pd (Guru BK)', status: 'Disetujui', antrian: 3, perkiraan: '09:30' },
    { id: 2, title: 'Masalah Adaptasi Lingkungan Baru di Sekolah', date: '02 Agu 2026', guru: 'Rio S.Pd (Guru BK)', status: 'Selesai', antrian: null, perkiraan: null },
  ]
  const prestasi = [
    { id: 1, title: 'Juara 1 Lomba Web Design SMK', date: '15 Jul 2026', level: 'Sekolah', poin: 25 },
  ]

  const statusColor = (s: string) => s === 'Disetujui' ? { bg: '#EEF2FF', c: P } : s === 'Selesai' ? { bg: '#F0FDF4', c: T } : { bg: '#FFFBEB', c: AM }

  return (
    <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9' }}>
      {/* Hero header */}
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '4px 20px 24px', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -60, right: -60, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', top: 0, right: 30, width: 110, height: 110, border: '1px solid rgba(255,255,255,0.05)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', bottom: -20, left: -30, width: 120, height: 120, background: 'rgba(255,255,255,0.04)', borderRadius: '50%', pointerEvents: 'none' }} />

        <FU d={0}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 16 }}>
            <div>
              <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 13, margin: 0, fontWeight: 500 }}>Selamat datang kembali 👋</p>
              <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 22, fontFamily: 'Nunito', margin: '4px 0 2px', letterSpacing: -0.5 }}>Ahmad Rizky</h1>
              <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 12, margin: 0 }}>Kelas X RPL 1 · Rekayasa Perangkat Lunak · NIS: 2024001</p>
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
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>1</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>0 selesai · <span style={{ color: P, fontWeight: 700 }}>1 aktif</span></div>
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
              <div style={{ color: '#1E293B', fontWeight: 900, fontSize: 28, fontFamily: 'Nunito', lineHeight: 1 }}>1</div>
              <div style={{ marginTop: 4, fontSize: 11, color: '#94A3B8' }}>Juara tingkat sekolah</div>
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
            {konseling.map((item, i) => {
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
          </div>
        )}

        {histTab === 'prestasi' && (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
            {prestasi.map((item, i) => (
              <div key={item.id} style={{ background: '#fff', borderRadius: 18, padding: 16, boxShadow: '0 2px 10px rgba(0,0,0,0.05)', animation: `fadeUp 0.35s ease ${i * 60}ms both` }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                  <div style={{ width: 44, height: 44, borderRadius: 14, background: `linear-gradient(135deg, ${AM} 0%, #D97706 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"><path d="M8 21h8M12 17v4M17 3H7l-2 9h12L17 3zM5 12a7 7 0 0014 0"/></svg>
                  </div>
                  <div>
                    <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: '#1E293B', margin: 0 }}>{item.title}</p>
                    <p style={{ fontSize: 11, color: '#94A3B8', margin: '4px 0 8px' }}>{item.date} · Tingkat {item.level}</p>
                    <span style={{ fontSize: 11, fontWeight: 800, padding: '3px 8px', borderRadius: 8, background: '#FEF3C7', color: '#D97706' }}>+{item.poin} poin prestasi</span>
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

function JadwalScreen({ navigate }: { navigate: (s: Screen) => void }) {
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

function RiwayatScreen() {
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

// ─── PROFIL SCREEN ────────────────────────────────────────────────────────────

function ProfilScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const menus: { icon: React.ReactNode; label: string; sub: string; screen: Screen; c: string; bg: string; danger?: boolean }[] = [
    {
      icon: <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>,
      label: 'Edit Profil', sub: 'Kelola informasi profil Anda', screen: 'editprofil', c: P, bg: '#EEF2FF',
    },
    {
      icon: <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="16" r="1" fill="currentColor"/></svg>,
      label: 'Ubah Password', sub: 'Keamanan & privasi akun', screen: 'ubahpassword', c: V, bg: '#F5F3FF',
    },
    {
      icon: <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>,
      label: 'Pengaturan Akun', sub: 'Atur preferensi & informasi akun', screen: 'pengaturan', c: AM, bg: '#FFFBEB',
    },
    {
      icon: <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>,
      label: 'Pusat Bantuan', sub: 'FAQ, panduan & kontak admin', screen: 'bantuan', c: T, bg: '#F0FDF4',
    },
    {
      icon: <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>,
      label: 'Keluar', sub: 'Logout dari akun ini', screen: 'login', c: RD, bg: '#FFF1F2', danger: true,
    },
  ]

  return (
    <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9' }}>
      {/* Header */}
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '4px 20px 32px', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -50, right: -50, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.07)', borderRadius: '50%', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', bottom: 0, left: -40, width: 120, height: 120, background: 'rgba(255,255,255,0.04)', borderRadius: '50%', pointerEvents: 'none' }} />
        <FU d={0}>
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', paddingTop: 8 }}>
            <div style={{ position: 'relative', marginBottom: 12 }}>
              <div style={{ width: 82, height: 82, borderRadius: '50%', background: 'rgba(255,255,255,0.18)', backdropFilter: 'blur(10px)', border: '2.5px solid rgba(255,255,255,0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 26, fontWeight: 900, color: '#fff', fontFamily: 'Nunito' }}>
                AR
              </div>
              <div style={{ position: 'absolute', bottom: -2, right: -2, width: 24, height: 24, borderRadius: '50%', background: T, border: '2px solid rgba(91,33,182)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" strokeLinecap="round"><path d="M20 6L9 17l-5-5"/></svg>
              </div>
            </div>
            <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 20, fontFamily: 'Nunito', margin: '0 0 4px', letterSpacing: -0.5 }}>Ahmad Rizky</h1>
            <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 13, margin: '0 0 10px' }}>Kelas X RPL 1 · NIS: 2024001</p>
            <div style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '5px 14px', borderRadius: 20, background: 'rgba(16,185,129,0.2)', border: '1px solid rgba(16,185,129,0.3)' }}>
              <div style={{ width: 7, height: 7, borderRadius: '50%', background: T, animation: 'pulseDot 1.5s ease-in-out infinite' }} />
              <span style={{ fontSize: 12, fontWeight: 700, color: '#6EE7B7', fontFamily: 'Nunito' }}>Akun Aktif</span>
            </div>
          </div>
        </FU>
      </div>

      {/* Stats strip */}
      <div style={{ margin: '-18px 16px 0', background: '#fff', borderRadius: 20, padding: '14px 0', display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', boxShadow: '0 6px 24px rgba(0,0,0,0.1)', position: 'relative', zIndex: 2 }}>
        {[{ label: 'Konseling', value: '1' }, { label: 'Prestasi', value: '1' }, { label: 'Poin BK', value: '15' }].map((s, i) => (
          <div key={i} style={{ textAlign: 'center', padding: '4px 12px', borderRight: i < 2 ? '1px solid #F1F5F9' : 'none' }}>
            <div style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 22, color: '#1E293B' }}>{s.value}</div>
            <div style={{ fontSize: 11, color: '#94A3B8', fontWeight: 600 }}>{s.label}</div>
          </div>
        ))}
      </div>

      {/* Menu */}
      <div style={{ padding: '16px 16px 24px', display: 'flex', flexDirection: 'column', gap: 10 }}>
        {menus.map((m, i) => (
          <FU key={i} d={i * 50}>
            <button
              onClick={() => navigate(m.screen)}
              style={{
                width: '100%',
                background: '#fff',
                borderRadius: 18,
                border: 'none',
                padding: '14px 16px',
                display: 'flex',
                alignItems: 'center',
                gap: 14,
                cursor: 'pointer',
                textAlign: 'left',
                boxShadow: '0 2px 10px rgba(0,0,0,0.05)',
                transition: 'transform 0.15s',
              }}
              onMouseDown={e => (e.currentTarget.style.transform = 'scale(0.98)')}
              onMouseUp={e => (e.currentTarget.style.transform = 'scale(1)')}
            >
              <div style={{ width: 44, height: 44, borderRadius: 14, background: m.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, color: m.c }}>
                {m.icon}
              </div>
              <div style={{ flex: 1, minWidth: 0 }}>
                <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 14, color: m.danger ? RD : '#1E293B', margin: '0 0 3px' }}>{m.label}</p>
                <p style={{ fontSize: 12, color: '#94A3B8', margin: 0 }}>{m.sub}</p>
              </div>
              {!m.danger && (
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="2.5" strokeLinecap="round"><path d="M9 18l6-6-6-6"/></svg>
              )}
            </button>
          </FU>
        ))}
      </div>
    </div>
  )
}

// ─── EDIT PROFIL SCREEN ───────────────────────────────────────────────────────

function EditProfilScreen({ navigate }: { navigate: (s: Screen) => void }) {
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

function UbahPasswordScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [old, setOld] = useState('')
  const [neu, setNeu] = useState('')
  const [conf, setConf] = useState('')
  const [loading, setLoading] = useState(false)
  const [done, setDone] = useState(false)

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

  if (done) {
    return (
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#F1F5F9', padding: '0 32px', animation: 'bounceIn 0.5s ease both' }}>
        <div style={{ width: 80, height: 80, borderRadius: '50%', background: `linear-gradient(135deg, ${V} 0%, ${P} 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20, boxShadow: `0 12px 32px rgba(124,58,237,0.45)` }}>
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        </div>
        <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 20, color: '#1E293B', textAlign: 'center', margin: '0 0 8px' }}>Password Diubah!</h2>
        <p style={{ color: '#64748B', fontSize: 13, textAlign: 'center' }}>Password akun Anda berhasil diperbarui. Gunakan password baru untuk login berikutnya.</p>
      </div>
    )
  }

  return (
    <>
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
            onClick={() => { setLoading(true); setTimeout(() => { setLoading(false); setDone(true) }, 1600) }}
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
              <><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg> Ubah Password</>
            )}
          </button>
        </FU>
      </div>
    </>
  )
}

// ─── PENGATURAN SCREEN ────────────────────────────────────────────────────────

function PengaturanScreen({ navigate }: { navigate: (s: Screen) => void }) {
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

function BantuanScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [openFaq, setOpenFaq] = useState<number | null>(null)
  const faqs = [
    { q: 'Bagaimana cara mengajukan konseling?', a: 'Buka halaman Jadwal dari menu bawah, isi form pengajuan dengan memilih tanggal, guru BK, sesi waktu, dan topik yang ingin didiskusikan, kemudian klik Kirim Pengajuan.' },
    { q: 'Bagaimana cara mengubah password akun saya?', a: 'Buka halaman Profil, pilih menu Ubah Password, masukkan password lama dan password baru minimal 6 karakter, lalu konfirmasi.' },
    { q: 'Bagaimana cara membuat laporan studi kasus?', a: 'Laporan studi kasus hanya dapat dibuat oleh Guru BK. Hubungi guru BK Anda untuk membuat laporan kasus yang diperlukan.' },
    { q: 'Bagaimana cara mengelola data poin siswa?', a: 'Data poin dikelola oleh administrator dan guru BK. Anda dapat melihat riwayat poin di halaman Dashboard pada tab Pelanggaran.' },
    { q: 'Bagaimana cara mencatat prestasi siswa?', a: 'Prestasi dicatat oleh Guru BK atau Wali Kelas. Informasikan prestasi Anda kepada guru terkait untuk dicatat ke dalam sistem.' },
  ]

  return (
    <>
      <SubHeader title="Pusat Bantuan" sub="FAQ, panduan & kontak admin" onBack={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>

        {/* Quick links */}
        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            {[
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2" strokeLinecap="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>, label: 'Panduan Penggunaan', sub: 'Cara pakai fitur-fitur aplikasi', c: P, bg: '#EEF2FF' },
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>, label: 'Hubungi Admin', sub: 'Kirim pesan ke pengelola sistem', c: T, bg: '#F0FDF4' },
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={AM} strokeWidth="2" strokeLinecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>, label: 'Tips & Trik', sub: 'Kiat sukses belajar dan berprestasi', c: AM, bg: '#FFFBEB' },
            ].map((item, i, arr) => (
              <button
                key={i}
                style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 14, padding: '14px 16px', background: 'none', border: 'none', borderBottom: i < arr.length - 1 ? '1px solid #F8FAFC' : 'none', cursor: 'pointer', textAlign: 'left', transition: 'background 0.15s' }}
                onMouseDown={e => (e.currentTarget.style.background = '#F8FAFC')}
                onMouseUp={e => (e.currentTarget.style.background = 'none')}
                onMouseLeave={e => (e.currentTarget.style.background = 'none')}
              >
                <div style={{ width: 38, height: 38, borderRadius: 12, background: item.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>{item.icon}</div>
                <div style={{ flex: 1 }}>
                  <p style={{ fontSize: 13, fontWeight: 800, color: '#1E293B', margin: 0, fontFamily: 'Nunito' }}>{item.label}</p>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '2px 0 0' }}>{item.sub}</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="2.5" strokeLinecap="round"><path d="M9 18l6-6-6-6"/></svg>
              </button>
            ))}
          </div>
        </FU>

        {/* FAQ */}
        <FU d={80}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 8 }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 14, color: '#1E293B', margin: 0 }}>Pertanyaan yang Sering Diajukan</p>
            </div>
            {faqs.map((faq, i) => (
              <div key={i} style={{ borderBottom: i < faqs.length - 1 ? '1px solid #F8FAFC' : 'none' }}>
                <button
                  onClick={() => setOpenFaq(openFaq === i ? null : i)}
                  style={{ width: '100%', padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, background: 'none', border: 'none', cursor: 'pointer', textAlign: 'left' }}
                >
                  <span style={{ fontSize: 13, fontWeight: 700, color: '#1E293B', fontFamily: 'Nunito', lineHeight: 1.4 }}>{faq.q}</span>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" strokeWidth="2.5" strokeLinecap="round" style={{ flexShrink: 0, transition: 'transform 0.3s', transform: openFaq === i ? 'rotate(180deg)' : 'none' }}><path d="M6 9l6 6 6-6"/></svg>
                </button>
                {openFaq === i && (
                  <div style={{ padding: '0 18px 14px', animation: 'fadeUp 0.25s ease both' }}>
                    <p style={{ fontSize: 13, color: '#64748B', lineHeight: 1.6, margin: 0 }}>{faq.a}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </FU>

        {/* Contact */}
        <FU d={160}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 16px', borderBottom: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 8 }}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2.5" strokeLinecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.7A2 2 0 012 .18h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 14, color: '#1E293B', margin: 0 }}>Hubungi Kami</p>
            </div>
            {[
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2" strokeLinecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>, label: 'Email', value: 'admin@sistembk.sch.id', c: P, bg: '#EEF2FF' },
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2" strokeLinecap="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>, label: 'WhatsApp', value: '+62 812-3456-7890', c: T, bg: '#F0FDF4' },
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2" strokeLinecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>, label: 'Lokasi', value: 'Ruang BK Lantai 2', c: RD, bg: '#FFF1F2' },
            ].map((item, i, arr) => (
              <button
                key={i}
                style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 14, padding: '13px 16px', background: 'none', border: 'none', borderBottom: i < arr.length - 1 ? '1px solid #F8FAFC' : 'none', cursor: 'pointer', textAlign: 'left', transition: 'background 0.15s' }}
                onMouseDown={e => (e.currentTarget.style.background = '#F8FAFC')}
                onMouseUp={e => (e.currentTarget.style.background = 'none')}
                onMouseLeave={e => (e.currentTarget.style.background = 'none')}
              >
                <div style={{ width: 36, height: 36, borderRadius: 11, background: item.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>{item.icon}</div>
                <div style={{ flex: 1 }}>
                  <p style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', margin: 0, textTransform: 'uppercase', letterSpacing: '0.04em' }}>{item.label}</p>
                  <p style={{ fontSize: 13, fontWeight: 700, color: '#1E293B', margin: '2px 0 0', fontFamily: 'Nunito' }}>{item.value}</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="2.5" strokeLinecap="round"><path d="M9 18l6-6-6-6"/></svg>
              </button>
            ))}
          </div>
        </FU>
      </div>
    </>
  )
}

// ─── MAIN APP ─────────────────────────────────────────────────────────────────

export default function App() {
  const [screen, setScreen] = useState<Screen>('login')
  const [tab, setTab] = useState<NavTab>('beranda')
  const [animKey, setAnimKey] = useState(0)
  const [scale, setScale] = useState(1)

  useEffect(() => {
    const updateScale = () => {
      const s = Math.min(0.9, (window.innerHeight - 80) / 860)
      setScale(parseFloat(s.toFixed(3)))
    }
    updateScale()
    window.addEventListener('resize', updateScale)
    return () => window.removeEventListener('resize', updateScale)
  }, [])

  const navigate = (to: Screen, forceTab?: NavTab) => {
    setScreen(to)
    setAnimKey(k => k + 1)
    if (forceTab) setTab(forceTab)
    else if (to === 'dashboard') setTab('beranda')
    else if (to === 'jadwal') setTab('jadwal')
    else if (to === 'riwayat') setTab('riwayat')
    else if (to === 'profil') setTab('profil')
  }

  const handleTabChange = (t: NavTab) => {
    const map: Record<NavTab, Screen> = { beranda: 'dashboard', jadwal: 'jadwal', riwayat: 'riwayat', profil: 'profil' }
    navigate(map[t], t)
  }

  const showNav = !['login', 'editprofil', 'ubahpassword', 'pengaturan', 'bantuan'].includes(screen)

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #0F172A 100%)', padding: '24px 16px', overflowY: 'auto' }}>
      {/* Background glow */}
      <div style={{ position: 'fixed', top: '15%', left: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(79,70,229,0.12) 0%, transparent 70%)`, pointerEvents: 'none' }} />
      <div style={{ position: 'fixed', bottom: '15%', right: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%)`, pointerEvents: 'none' }} />

      {/* Phone shell */}
      <div style={{
        width: 370,
        height: 800,
        background: 'linear-gradient(145deg, #E8E8ED 0%, #C8C8CF 60%, #B8B8BF 100%)',
        borderRadius: 48,
        padding: '11px 6px 9px',
        boxShadow: '0 0 0 1px rgba(255,255,255,0.8) inset, 0 0 0 1.5px #9C9CA8, 0 24px 64px rgba(0,0,0,0.55), 0 8px 20px rgba(0,0,0,0.3)',
        position: 'relative',
        flexShrink: 0,
        transform: `scale(${scale})`,
        transformOrigin: 'top center',
        marginBottom: scale < 1 ? `${(800 * scale - 800)}px` : 0,
      }}>
        {/* Dynamic island / notch */}
        <div style={{ position: 'absolute', top: 13, left: '50%', transform: 'translateX(-50%)', width: 110, height: 28, background: '#1A1A1A', borderRadius: 50, zIndex: 30, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, pointerEvents: 'none' }}>
          <div style={{ width: 9, height: 9, borderRadius: '50%', background: '#2C2C2C' }} />
          <div style={{ width: 48, height: 9, borderRadius: 20, background: '#2C2C2C' }} />
        </div>
        {/* Side buttons */}
        <div style={{ position: 'absolute', right: -3, top: 122, width: 3, height: 54, background: '#A0A0A8', borderRadius: '0 2px 2px 0', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 102, width: 3, height: 32, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 146, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 214, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />

        {/* Screen */}
        <div style={{ width: '100%', height: '100%', borderRadius: 40, overflow: 'hidden', background: '#F1F5F9', display: 'flex', flexDirection: 'column' }}>
          <StatusBar dark={screen === 'login'} />

          {/* Animated content */}
          <SIR key={animKey} k={animKey}>
            {screen === 'login' && <LoginScreen onLogin={() => navigate('dashboard')} />}
            {screen === 'dashboard' && <DashboardScreen navigate={navigate} />}
            {screen === 'jadwal' && <JadwalScreen navigate={navigate} />}
            {screen === 'riwayat' && <RiwayatScreen />}
            {screen === 'profil' && <ProfilScreen navigate={navigate} />}
            {screen === 'editprofil' && <EditProfilScreen navigate={navigate} />}
            {screen === 'ubahpassword' && <UbahPasswordScreen navigate={navigate} />}
            {screen === 'pengaturan' && <PengaturanScreen navigate={navigate} />}
            {screen === 'bantuan' && <BantuanScreen navigate={navigate} />}
          </SIR>

          {showNav && <BottomNav tab={tab} onTab={handleTabChange} />}
        </div>
      </div>
    </div>
  )
}
