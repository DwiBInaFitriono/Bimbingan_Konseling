import React, { useState } from 'react';
import { P, RD } from '../constants';

export function InputField({
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
