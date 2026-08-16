import React from 'react';
import { NavTab } from '../types';
import { P } from '../constants';

export function BottomNav({ tab, onTab }: { tab: NavTab; onTab: (t: NavTab) => void }) {
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

export function NavIcon({ id, c, active }: { id: string; c: string; active: boolean }) {
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
