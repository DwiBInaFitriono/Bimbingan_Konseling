import React from 'react';
import { NavTab } from '../types';
import { COLOR_PRIMARY } from '../constants';

export function BottomNav({
  currentActiveTab,
  onSelectTab
}: {
  currentActiveTab: NavTab;
  onSelectTab: (selectedTab: NavTab) => void;
}) {
  const bottomNavigationItems: { tabKey: NavTab; displayLabel: string }[] = [
    { tabKey: 'beranda', displayLabel: 'Beranda' },
    { tabKey: 'jadwal', displayLabel: 'Jadwal' },
    { tabKey: 'riwayat', displayLabel: 'Riwayat' },
    { tabKey: 'profil', displayLabel: 'Profil' },
  ];

  return (
    <div
      className="flex-shrink-0 flex items-stretch"
      style={{ height: 64, background: '#fff', borderTop: '1px solid #F1F5F9', paddingBottom: 6 }}
    >
      {bottomNavigationItems.map(({ tabKey, displayLabel }) => {
        const isTabCurrentlyActive = currentActiveTab === tabKey;
        const currentIconColor = isTabCurrentlyActive ? COLOR_PRIMARY : '#94A3B8';
        return (
          <button
            key={tabKey}
            onClick={() => onSelectTab(tabKey)}
            className="flex-1 flex flex-col items-center justify-center gap-1 relative"
            style={{ border: 'none', background: 'transparent', cursor: 'pointer', transition: 'all 0.2s' }}
          >
            {isTabCurrentlyActive && (
              <div
                style={{ position: 'absolute', top: 0, left: '50%', transform: 'translateX(-50%)', width: 28, height: 3, background: COLOR_PRIMARY, borderRadius: '0 0 4px 4px' }}
              />
            )}
            <NavIcon tabKey={tabKey} iconColor={currentIconColor} isTabActive={isTabCurrentlyActive} />
            <span style={{ fontSize: 10, fontWeight: 700, color: currentIconColor, fontFamily: 'Nunito', lineHeight: 1 }}>{displayLabel}</span>
          </button>
        );
      })}
    </div>
  );
}

export function NavIcon({ tabKey, iconColor, isTabActive }: { tabKey: string; iconColor: string; isTabActive: boolean }) {
  const iconPixelSize = 22;
  switch (tabKey) {
    case 'beranda':
      return (
        <svg width={iconPixelSize} height={iconPixelSize} viewBox="0 0 24 24" fill={isTabActive ? iconColor : 'none'} stroke={iconColor} strokeWidth={isTabActive ? 0 : 2} strokeLinecap="round" strokeLinejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" fill={isTabActive ? iconColor : 'none'} stroke={iconColor} strokeWidth={isTabActive ? 0 : 2} />
          <polyline points="9 22 9 12 15 12 15 22" stroke={isTabActive ? '#fff' : iconColor} strokeWidth="2" fill="none" />
        </svg>
      );
    case 'jadwal':
      return (
        <svg width={iconPixelSize} height={iconPixelSize} viewBox="0 0 24 24" fill={isTabActive ? iconColor : 'none'} stroke={iconColor} strokeWidth={1.8} strokeLinecap="round">
          <rect x="3" y="4" width="18" height="18" rx="3" />
          <path d="M16 2v4M8 2v4M3 10h18" stroke={isTabActive ? '#fff' : iconColor} />
          <circle cx="8" cy="15" r="1.2" fill={isTabActive ? '#fff' : iconColor} />
          <circle cx="12" cy="15" r="1.2" fill={isTabActive ? '#fff' : iconColor} />
          <circle cx="16" cy="15" r="1.2" fill={isTabActive ? '#fff' : iconColor} />
        </svg>
      );
    case 'riwayat':
      return (
        <svg width={iconPixelSize} height={iconPixelSize} viewBox="0 0 24 24" fill="none" stroke={iconColor} strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
          <circle cx="12" cy="12" r="9" fill={isTabActive ? iconColor : 'none'} />
          <polyline points="12 7 12 12 15 15" stroke={isTabActive ? '#fff' : iconColor} strokeWidth="2" />
        </svg>
      );
    case 'profil':
      return (
        <svg width={iconPixelSize} height={iconPixelSize} viewBox="0 0 24 24" fill={isTabActive ? iconColor : 'none'} stroke={iconColor} strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" fill={isTabActive ? iconColor : 'none'} />
          <circle cx="12" cy="7" r="4" fill={isTabActive ? iconColor : 'none'} />
        </svg>
      );
    default:
      return null;
  }
}
