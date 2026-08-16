import React from 'react';
import { useState, useEffect } from 'react';
import { T } from '../constants';

export function StatusBar({ dark }: { dark: boolean }) {
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
