import React from 'react';
import { P, T, IND } from '../constants';

export function FU({ children, d = 0, cls = '' }: { children: React.ReactNode; d?: number; cls?: string }) {
  return (
    <div className={cls} style={{ animation: `fadeUp 0.42s ease ${d}ms both` }}>
      {children}
    </div>
  )
}

export function SIR({ children, k }: { children: React.ReactNode; k: number }) {
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
