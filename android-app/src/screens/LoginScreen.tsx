import React, { useState, useEffect } from 'react';
import { ApiService } from '../services/api';
import { COLOR_PRIMARY, COLOR_VIOLET, COLOR_DANGER, COLOR_INDIGO } from '../constants';
import { FadeUpAnimation } from '../components/Animations';

export function LoginScreen({ onLogin }: { onLogin: () => void }) {
  const [studentIdentificationNumber, setStudentIdentificationNumber] = useState('');
  const [accountPassword, setAccountPassword] = useState('');
  const [isPasswordVisible, setIsPasswordVisible] = useState(false);
  const [isLoadingAuthentication, setIsLoadingAuthentication] = useState(false);
  const [authenticationErrorMessage, setAuthenticationErrorMessage] = useState('');
  const [isNotificationToastVisible, setIsNotificationToastVisible] = useState(false);
  const [isNotificationToastLeaving, setIsNotificationToastLeaving] = useState(false);
  const [isNisInputFocused, setIsNisInputFocused] = useState(false);
  const [isPasswordInputFocused, setIsPasswordInputFocused] = useState(false);

  useEffect(() => {
    if (!authenticationErrorMessage) return;
    setIsNotificationToastVisible(true);
    setIsNotificationToastLeaving(false);
    const dismissNotificationTimer = setTimeout(() => {
      setIsNotificationToastLeaving(true);
      setTimeout(() => {
        setIsNotificationToastVisible(false);
        setAuthenticationErrorMessage('');
      }, 400);
    }, 3000);
    return () => clearTimeout(dismissNotificationTimer);
  }, [authenticationErrorMessage]);

  const handleAuthenticationSubmit = async () => {
    if (!studentIdentificationNumber || !accountPassword) {
      setAuthenticationErrorMessage('Lengkapi semua field terlebih dahulu');
      return;
    }
    setAuthenticationErrorMessage('');
    setIsLoadingAuthentication(true);
    
    try {
      const loginResponse = await ApiService.login(studentIdentificationNumber, accountPassword);
      if (loginResponse.success) {
        localStorage.setItem('student_data', JSON.stringify(loginResponse.student));
        onLogin();
      } else {
        setAuthenticationErrorMessage(loginResponse.message || 'Login gagal');
      }
    } catch (networkError: any) {
      setAuthenticationErrorMessage(networkError.message || 'Terjadi kesalahan jaringan');
    } finally {
      setIsLoadingAuthentication(false);
    }
  };

  return (
    <div style={{ flex: 1, display: 'flex', flexDirection: 'column', background: `linear-gradient(145deg, #5B21B6 0%, ${COLOR_PRIMARY} 45%, ${COLOR_INDIGO} 75%, ${COLOR_VIOLET} 100%)`, position: 'relative', overflow: 'hidden' }}>

      {isNotificationToastVisible && (
        <div
          style={{
            position: 'absolute',
            top: 16,
            right: 12,
            zIndex: 999,
            maxWidth: 260,
            background: '#fff',
            borderRadius: 16,
            padding: '12px 14px',
            boxShadow: '0 8px 32px rgba(0,0,0,0.22), 0 2px 8px rgba(239,68,68,0.18)',
            display: 'flex',
            alignItems: 'center',
            gap: 10,
            animation: `${isNotificationToastLeaving ? 'toastOut' : 'toastIn'} 0.4s cubic-bezier(0.22,1,0.36,1) both`,
            borderLeft: `4px solid ${COLOR_DANGER}`,
          }}
        >
          <div style={{ width: 32, height: 32, borderRadius: 10, background: '#FEF2F2', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke={COLOR_DANGER} strokeWidth="2.5" strokeLinecap="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <div style={{ flex: 1 }}>
            <p style={{ margin: 0, fontSize: 11, fontWeight: 700, color: '#64748B', textTransform: 'uppercase', letterSpacing: '0.04em', fontFamily: 'Nunito' }}>Perhatian</p>
            <p style={{ margin: '2px 0 0', fontSize: 12, fontWeight: 600, color: '#1E293B', lineHeight: 1.4 }}>{authenticationErrorMessage}</p>
          </div>
          <button
            onClick={() => {
              setIsNotificationToastLeaving(true);
              setTimeout(() => {
                setIsNotificationToastVisible(false);
                setAuthenticationErrorMessage('');
              }, 400);
            }}
            style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', padding: 0, display: 'flex', flexShrink: 0 }}
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>
      )}

      <div style={{ position: 'absolute', inset: 0, pointerEvents: 'none', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: '8%', left: '-12%', width: 220, height: 220, background: 'rgba(255,255,255,0.07)', borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%', animation: 'blob 9s ease-in-out infinite, float 7s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '18%', right: '-8%', width: 160, height: 160, background: 'rgba(255,255,255,0.09)', borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%', animation: 'blob 11s ease-in-out infinite reverse, float2 8s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '40%', left: '8%', width: 70, height: 70, background: `rgba(16,185,129,0.25)`, borderRadius: '50%', animation: 'float3 5.5s ease-in-out infinite' }} />
        <div style={{ position: 'absolute', top: '30%', right: '12%', width: 44, height: 44, background: `rgba(245,158,11,0.25)`, borderRadius: '50%', animation: 'float2 4.5s ease-in-out infinite 1s' }} />
        <div style={{ position: 'absolute', top: '55%', left: '18%', width: 28, height: 28, background: 'rgba(255,255,255,0.18)', borderRadius: '50%', animation: 'float 3.8s ease-in-out infinite 0.8s' }} />
        <div style={{ position: 'absolute', top: '12%', left: '40%', width: 18, height: 18, background: 'rgba(255,255,255,0.15)', borderRadius: '50%', animation: 'float2 3s ease-in-out infinite 2s' }} />
        <div style={{ position: 'absolute', top: -90, right: -90, width: 300, height: 300, border: '1px solid rgba(255,255,255,0.08)', borderRadius: '50%' }} />
        <div style={{ position: 'absolute', top: -50, right: -50, width: 200, height: 200, border: '1px solid rgba(255,255,255,0.06)', borderRadius: '50%' }} />
      </div>

      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '32px 32px 0', position: 'relative', zIndex: 1 }}>
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ position: 'relative', marginBottom: 16, display: 'flex', justifyContent: 'center' }}>
            <div
              style={{ width: 84, height: 84, borderRadius: 24, background: 'rgba(255,255,255,0.15)', backdropFilter: 'blur(12px)', border: '1.5px solid rgba(255,255,255,0.25)', display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 12px 40px rgba(0,0,0,0.25)', animation: 'rippleOut 2.5s ease-in-out infinite' }}
            >
              <span style={{ fontSize: 30, fontWeight: 900, color: '#fff', fontFamily: 'Nunito', letterSpacing: -1 }}>BK</span>
            </div>
          </div>
        </FadeUpAnimation>
        <FadeUpAnimation delayMilliseconds={80}>
          <div style={{ textAlign: 'center' }}>
            <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 26, fontFamily: 'Nunito', margin: 0, letterSpacing: -0.5 }}>SistemBK</h1>
            <p style={{ color: 'rgba(255,255,255,0.85)', fontSize: 13, margin: '6px 0 0', fontWeight: 500, paddingBottom: 28 }}>Layanan Bimbingan dan Konseling Siswa</p>
          </div>
        </FadeUpAnimation>
      </div>

      <FadeUpAnimation delayMilliseconds={160}>
        <div
          style={{ background: '#fff', borderRadius: '32px 32px 0 0', padding: '28px 24px 24px', position: 'relative', boxShadow: '0 -24px 60px rgba(0,0,0,0.18)', zIndex: 1 }}
        >
          <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 22, color: '#1E293B', margin: '0 0 4px' }}>Masuk ke Akun Siswa</h2>
          <p style={{ color: '#64748B', fontSize: 13, margin: '0 0 20px', fontWeight: 500 }}>Masukkan NIS dan kata sandi Anda untuk melanjutkan</p>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            <div>
              <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                NIS / Username <span style={{ color: COLOR_DANGER }}>*</span>
              </label>
              <div style={{ position: 'relative' }}>
                <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: isNisInputFocused ? COLOR_PRIMARY : '#94A3B8', transition: 'color 0.2s' }}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
                <input
                  type="text"
                  value={studentIdentificationNumber}
                  onChange={event => setStudentIdentificationNumber(event.target.value)}
                  placeholder="Masukkan NIS Anda"
                  onFocus={() => setIsNisInputFocused(true)}
                  onBlur={() => setIsNisInputFocused(false)}
                  style={{ width: '100%', paddingLeft: 42, paddingRight: 16, paddingTop: 14, paddingBottom: 14, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${isNisInputFocused ? COLOR_PRIMARY : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
                />
              </div>
            </div>

            <div>
              <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                Password <span style={{ color: COLOR_DANGER }}>*</span>
              </label>
              <div style={{ position: 'relative' }}>
                <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: isPasswordInputFocused ? COLOR_PRIMARY : '#94A3B8', transition: 'color 0.2s' }}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                <input
                  type={isPasswordVisible ? 'text' : 'password'}
                  value={accountPassword}
                  onChange={event => setAccountPassword(event.target.value)}
                  placeholder="Masukkan password"
                  onFocus={() => setIsPasswordInputFocused(true)}
                  onBlur={() => setIsPasswordInputFocused(false)}
                  style={{ width: '100%', paddingLeft: 42, paddingRight: 44, paddingTop: 14, paddingBottom: 14, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${isPasswordInputFocused ? COLOR_PRIMARY : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
                />
                <button
                  onClick={() => setIsPasswordVisible(previousState => !previousState)}
                  style={{ position: 'absolute', right: 14, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', padding: 0, display: 'flex' }}
                >
                  {isPasswordVisible
                    ? <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/></svg>
                    : <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  }
                </button>
              </div>
            </div>
          </div>

          <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: 8, marginBottom: 20 }}>
            <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: COLOR_PRIMARY, fontSize: 13, fontWeight: 700, fontFamily: 'Nunito' }}>
              Lupa Password?
            </button>
          </div>

          <button
            onClick={handleAuthenticationSubmit}
            disabled={isLoadingAuthentication}
            style={{
              width: '100%',
              padding: '16px',
              borderRadius: 18,
              border: 'none',
              cursor: isLoadingAuthentication ? 'not-allowed' : 'pointer',
              background: isLoadingAuthentication ? '#94A3B8' : `linear-gradient(135deg, ${COLOR_PRIMARY} 0%, ${COLOR_VIOLET} 100%)`,
              boxShadow: isLoadingAuthentication ? 'none' : `0 10px 28px rgba(79,70,229,0.45)`,
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
            onMouseDown={mouseEvent => { if (!isLoadingAuthentication) (mouseEvent.currentTarget as HTMLButtonElement).style.transform = 'scale(0.97)'; }}
            onMouseUp={mouseEvent => (mouseEvent.currentTarget as HTMLButtonElement).style.transform = 'scale(1)'}
          >
            {isLoadingAuthentication ? (
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

          <p style={{ textAlign: 'center', fontSize: 12, color: '#64748B', marginTop: 14, marginBottom: 0, fontWeight: 500 }}>
            Butuh bantuan? Hubungi admin sekolah
          </p>
        </div>
      </FadeUpAnimation>
    </div>
  );
}

