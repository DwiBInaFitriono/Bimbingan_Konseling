import React from 'react';

export function FadeUpAnimation({
  children,
  delayMilliseconds = 0,
  d,
  className = ''
}: {
  children: React.ReactNode;
  delayMilliseconds?: number;
  d?: number;
  className?: string;
}) {
  const actualDelay = d !== undefined ? d : delayMilliseconds;
  return (
    <div className={className} style={{ animation: `fadeUp 0.42s ease ${actualDelay}ms both` }}>
      {children}
    </div>
  );
}

export function SlideInRightAnimation({
  children,
  animationKey,
  k,
}: {
  children: React.ReactNode;
  animationKey?: number;
  k?: number;
}) {
  const resolvedKey = k !== undefined ? k : animationKey;
  return (
    <div
      key={resolvedKey}
      style={{ animation: 'slideInRight 0.38s cubic-bezier(0.22,1,0.36,1) both', flex: 1, minHeight: 0, display: 'flex', flexDirection: 'column', overflow: 'hidden' }}
    >
      {children}
    </div>
  );
}

export const FU = FadeUpAnimation;
export const SIR = SlideInRightAnimation;
