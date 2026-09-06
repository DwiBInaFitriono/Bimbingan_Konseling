import React from 'react';
import { useState, useEffect } from 'react';
import { COLOR_SUCCESS } from '../constants';

export function StatusBar({ dark = true }: { dark?: boolean }) {
  const [formattedCurrentTime, setFormattedCurrentTime] = useState(() => {
    const currentDate = new Date();
    return `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}`;
  });

  useEffect(() => {
    const timeUpdateIntervalId = setInterval(() => {
      const currentDate = new Date();
      setFormattedCurrentTime(`${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}`);
    }, 30000);
    return () => clearInterval(timeUpdateIntervalId);
  }, []);

  const contentTextColor = dark ? '#FFFFFF' : '#1E293B';
  const statusBarBackgroundColor = dark ? '#5B21B6' : '#FFFFFF';

  return (
    <div
      className="flex justify-between items-center px-6 flex-shrink-0"
      style={{ height: 44, color: contentTextColor, background: statusBarBackgroundColor, fontFamily: 'Nunito', transition: 'all 0.3s' }}
    >
      <span style={{ fontSize: 14, fontWeight: 800 }}>{formattedCurrentTime}</span>
      <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
        <div style={{ display: 'flex', alignItems: 'flex-end', gap: 2 }}>
          {[4, 6, 8, 11].map((barHeight, barIndex) => (
            <div key={barIndex} style={{ width: 3, height: barHeight, background: contentTextColor, borderRadius: 1, opacity: barIndex < 3 ? 1 : 0.4 }} />
          ))}
        </div>
        <svg width="16" height="12" viewBox="0 0 22 16" fill="none">
          <path d="M1 4.5C4.3 1.5 7.9 0 11 0s6.7 1.5 10 4.5" stroke={contentTextColor} strokeWidth="2" strokeLinecap="round"/>
          <path d="M3.5 7.5C6 5 8.7 4 11 4s5 1 7.5 3.5" stroke={contentTextColor} strokeWidth="2" strokeLinecap="round"/>
          <path d="M6.5 10.5C8 9 9.5 8.5 11 8.5s3 .5 4.5 2" stroke={contentTextColor} strokeWidth="2" strokeLinecap="round"/>
          <circle cx="11" cy="15" r="1.5" fill={contentTextColor}/>
        </svg>
        <div style={{ display: 'flex', alignItems: 'center' }}>
          <div style={{ width: 22, height: 11, border: `1.5px solid ${contentTextColor}`, borderRadius: 3, padding: '1.5px', display: 'flex', alignItems: 'center' }}>
            <div style={{ width: '75%', height: '100%', background: COLOR_SUCCESS, borderRadius: 1.5 }} />
          </div>
          <div style={{ width: 2, height: 6, background: contentTextColor, borderRadius: '0 2px 2px 0', marginLeft: 1 }} />
        </div>
      </div>
    </div>
  );
}
