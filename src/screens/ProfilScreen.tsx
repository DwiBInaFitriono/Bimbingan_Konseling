import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function ProfilScreen({ navigate }: { navigate: (s: Screen) => void }) {
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
      <div style={{ background: `linear-gradient(135deg, #5B21B6 0%, ${P} 55%, ${IND} 100%)`, padding: '16px 20px 32px', position: 'relative', overflow: 'hidden' }}>
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
