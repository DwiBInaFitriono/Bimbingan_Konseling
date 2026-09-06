import React, { useState } from 'react';
import { COLOR_PRIMARY, COLOR_DANGER } from '../constants';

export function InputField({
  label,
  required = false,
  value,
  onChange,
  placeholder,
  type = 'text',
  icon,
  disabled = false,
  readOnly = false,
}: {
  label: string;
  required?: boolean;
  value: string;
  onChange: (newValue: string) => void;
  placeholder: string;
  type?: string;
  icon?: React.ReactNode;
  disabled?: boolean;
  readOnly?: boolean;
}) {
  const [isInputFocused, setIsInputFocused] = useState(false);
  const isFieldLocked = disabled || readOnly;

  return (
    <div>
      <label style={{ fontSize: 12, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.04em' }}>
        {label} {required && <span style={{ color: COLOR_DANGER }}>*</span>}
      </label>
      <div style={{ position: 'relative' }}>
        {icon && (
          <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: isInputFocused && !isFieldLocked ? COLOR_PRIMARY : '#94A3B8', transition: 'color 0.2s' }}>
            {icon}
          </div>
        )}
        <input
          type={type}
          value={value}
          onChange={changeEvent => !isFieldLocked && onChange(changeEvent.target.value)}
          placeholder={placeholder}
          disabled={disabled}
          readOnly={readOnly}
          onFocus={() => !isFieldLocked && setIsInputFocused(true)}
          onBlur={() => setIsInputFocused(false)}
          style={{
            width: '100%',
            paddingLeft: icon ? 42 : 16,
            paddingRight: 16,
            paddingTop: 13,
            paddingBottom: 13,
            borderRadius: 14,
            fontSize: 14,
            color: isFieldLocked ? '#64748B' : '#1E293B',
            fontFamily: 'Inter',
            background: isFieldLocked ? '#F1F5F9' : '#F8FAFC',
            border: `1.5px solid ${isInputFocused && !isFieldLocked ? COLOR_PRIMARY : '#E2E8F0'}`,
            outline: 'none',
            transition: 'border-color 0.2s',
            boxSizing: 'border-box',
            cursor: isFieldLocked ? 'not-allowed' : 'text',
          }}
        />
      </div>
    </div>
  );
}

