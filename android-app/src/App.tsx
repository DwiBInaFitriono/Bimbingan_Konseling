import { useState, useEffect } from 'react'
import { Screen, NavTab } from './types'
import { LoginScreen } from './screens/LoginScreen'
import { DashboardScreen } from './screens/DashboardScreen'
import { JadwalScreen } from './screens/JadwalScreen'
import { RiwayatScreen } from './screens/RiwayatScreen'
import { ProfilScreen } from './screens/ProfilScreen'
import { EditProfilScreen } from './screens/EditProfilScreen'
import { UbahPasswordScreen } from './screens/UbahPasswordScreen'
import { PengaturanScreen } from './screens/PengaturanScreen'
import { BantuanScreen } from './screens/BantuanScreen'
import { BottomNav } from './components/BottomNav'
import { StatusBar } from './components/StatusBar'
import { SIR } from './components/Animations'

export default function App() {
  const [currentScreen, setCurrentScreen] = useState<Screen>('login');
  const [activeNavigationTab, setActiveNavigationTab] = useState<NavTab>('beranda');
  const [screenAnimationKey, setScreenAnimationKey] = useState(0);
  const [deviceScaleFactor, setDeviceScaleFactor] = useState(1);
  const [isMobileViewport, setIsMobileViewport] = useState(false);

  useEffect(() => {
    const updateDeviceLayout = () => {
      const isMobileDevice = window.innerWidth <= 480;
      setIsMobileViewport(isMobileDevice);
      if (!isMobileDevice) {
        const calculatedScale = Math.min(0.9, (window.innerHeight - 80) / 860);
        setDeviceScaleFactor(parseFloat(calculatedScale.toFixed(3)));
      }
    };
    updateDeviceLayout();
    window.addEventListener('resize', updateDeviceLayout);
    return () => window.removeEventListener('resize', updateDeviceLayout);
  }, []);

  const navigateToScreen = (targetScreen: Screen, forcedTab?: NavTab) => {
    setCurrentScreen(targetScreen);
    setScreenAnimationKey(previousKey => previousKey + 1);
    if (forcedTab) {
      setActiveNavigationTab(forcedTab);
    } else if (targetScreen === 'dashboard') {
      setActiveNavigationTab('beranda');
    } else if (targetScreen === 'jadwal') {
      setActiveNavigationTab('jadwal');
    } else if (targetScreen === 'riwayat') {
      setActiveNavigationTab('riwayat');
    } else if (targetScreen === 'profil') {
      setActiveNavigationTab('profil');
    }
  };

  const handleNavigationTabChange = (selectedTab: NavTab) => {
    const tabToScreenMapping: Record<NavTab, Screen> = {
      beranda: 'dashboard',
      jadwal: 'jadwal',
      riwayat: 'riwayat',
      profil: 'profil',
    };
    navigateToScreen(tabToScreenMapping[selectedTab], selectedTab);
  };

  const shouldDisplayBottomNavigation = !['login', 'editprofil', 'ubahpassword', 'pengaturan', 'bantuan'].includes(currentScreen);

  const renderApplicationContent = () => (
    <div style={{ width: '100%', height: '100%', borderRadius: isMobileViewport ? 0 : 40, overflow: 'hidden', background: '#F1F5F9', display: 'flex', flexDirection: 'column' }}>
      <StatusBar dark={true} />

      <SIR key={screenAnimationKey} k={screenAnimationKey}>
        {currentScreen === 'login' && <LoginScreen onLogin={() => navigateToScreen('dashboard')} />}
        {currentScreen === 'dashboard' && <DashboardScreen navigate={navigateToScreen} />}
        {currentScreen === 'jadwal' && <JadwalScreen navigate={navigateToScreen} />}
        {currentScreen === 'riwayat' && <RiwayatScreen />}
        {currentScreen === 'profil' && <ProfilScreen navigate={navigateToScreen} />}
        {currentScreen === 'editprofil' && <EditProfilScreen navigate={navigateToScreen} />}
        {currentScreen === 'ubahpassword' && <UbahPasswordScreen navigate={navigateToScreen} />}
        {currentScreen === 'pengaturan' && <PengaturanScreen navigate={navigateToScreen} />}
        {currentScreen === 'bantuan' && <BantuanScreen navigate={navigateToScreen} />}
      </SIR>

      {shouldDisplayBottomNavigation && <BottomNav currentActiveTab={activeNavigationTab} onSelectTab={handleNavigationTabChange} />}
    </div>
  );

  if (isMobileViewport) {
    return (
      <div style={{ width: '100vw', minHeight: '100vh', background: '#0F172A', display: 'flex', flexDirection: 'column' }}>
        {renderApplicationContent()}
      </div>
    );
  }

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #0F172A 100%)', padding: '24px 16px', overflowY: 'auto' }}>
      <div style={{ position: 'fixed', top: '15%', left: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(79,70,229,0.12) 0%, transparent 70%)`, pointerEvents: 'none' }} />
      <div style={{ position: 'fixed', bottom: '15%', right: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%)`, pointerEvents: 'none' }} />

      <div style={{
        width: 370,
        height: 800,
        background: 'linear-gradient(145deg, #E8E8ED 0%, #C8C8CF 60%, #B8B8BF 100%)',
        borderRadius: 48,
        padding: '11px 6px 9px',
        boxShadow: '0 0 0 1px rgba(255,255,255,0.8) inset, 0 0 0 1.5px #9C9CA8, 0 24px 64px rgba(0,0,0,0.55), 0 8px 20px rgba(0,0,0,0.3)',
        position: 'relative',
        flexShrink: 0,
        transform: `scale(${deviceScaleFactor})`,
        transformOrigin: 'top center',
        marginBottom: deviceScaleFactor < 1 ? `${(800 * deviceScaleFactor - 800)}px` : 0,
      }}>
        <div style={{ position: 'absolute', top: 13, left: '50%', transform: 'translateX(-50%)', width: 110, height: 28, background: '#1A1A1A', borderRadius: 50, zIndex: 30, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, pointerEvents: 'none' }}>
          <div style={{ width: 9, height: 9, borderRadius: '50%', background: '#2C2C2C' }} />
          <div style={{ width: 48, height: 9, borderRadius: 20, background: '#2C2C2C' }} />
        </div>
        <div style={{ position: 'absolute', right: -3, top: 122, width: 3, height: 54, background: '#A0A0A8', borderRadius: '0 2px 2px 0', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 102, width: 3, height: 32, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 146, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 214, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />

        {renderApplicationContent()}
      </div>
    </div>
  );
}

