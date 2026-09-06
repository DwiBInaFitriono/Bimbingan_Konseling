import React from 'react';
import { COLOR_PRIMARY, COLOR_INDIGO } from '../constants';

export function SubHeader({
  title,
  sub,
  subtitle,
  onBack,
  headerTitle,
  headerSubtitle,
  onBackPress,
}: {
  title?: string;
  sub?: string;
  subtitle?: string;
  onBack?: () => void;
  headerTitle?: string;
  headerSubtitle?: string;
  onBackPress?: () => void;
}) {
  const displayTitle = headerTitle ?? title ?? '';
  const displaySubtitle = headerSubtitle ?? subtitle ?? sub ?? '';
  const handleBackAction = onBackPress ?? onBack ?? (() => {});

  return (
    <div
      className="flex-shrink-0 px-4 pb-4"
      style={{
        background: `linear-gradient(135deg, #5B21B6 0%, ${COLOR_PRIMARY} 60%, ${COLOR_INDIGO} 100%)`,
        paddingTop: 16,
        position: 'relative',
        overflow: 'hidden',
      }}
    >
      <div style={{ position: 'absolute', top: -40, right: -40, width: 160, height: 160, border: '1px solid rgba(255,255,255,0.08)', borderRadius: '50%', pointerEvents: 'none' }} />
      <div style={{ position: 'absolute', top: 10, right: 20, width: 80, height: 80, border: '1px solid rgba(255,255,255,0.06)', borderRadius: '50%', pointerEvents: 'none' }} />
      <div className="flex items-center gap-3">
        <button
          onClick={handleBackAction}
          style={{ width: 36, height: 36, borderRadius: 12, background: 'rgba(255,255,255,0.15)', border: 'none', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer', flexShrink: 0 }}
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
            <path d="M19 12H5M12 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 style={{ color: '#fff', fontWeight: 900, fontSize: 18, fontFamily: 'Nunito', margin: 0, lineHeight: 1.2 }}>{displayTitle}</h1>
          <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, margin: 0, marginTop: 2 }}>{displaySubtitle}</p>
        </div>
      </div>
    </div>
  );
}
