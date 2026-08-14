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
  const [screen, setScreen] = useState<Screen>('login')
  const [tab, setTab] = useState<NavTab>('beranda')
  const [animKey, setAnimKey] = useState(0)
  const [scale, setScale] = useState(1)

  useEffect(() => {
    const updateScale = () => {
      const s = Math.min(0.9, (window.innerHeight - 80) / 860)
      setScale(parseFloat(s.toFixed(3)))
    }
    updateScale()
    window.addEventListener('resize', updateScale)
    return () => window.removeEventListener('resize', updateScale)
  }, [])

  const navigate = (to: Screen, forceTab?: NavTab) => {
    setScreen(to)
    setAnimKey(k => k + 1)
    if (forceTab) setTab(forceTab)
    else if (to === 'dashboard') setTab('beranda')
    else if (to === 'jadwal') setTab('jadwal')
    else if (to === 'riwayat') setTab('riwayat')
    else if (to === 'profil') setTab('profil')
  }

  const handleTabChange = (t: NavTab) => {
    const map: Record<NavTab, Screen> = { beranda: 'dashboard', jadwal: 'jadwal', riwayat: 'riwayat', profil: 'profil' }
    navigate(map[t], t)
  }

  const showNav = !['login', 'editprofil', 'ubahpassword', 'pengaturan', 'bantuan'].includes(screen)

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #0F172A 100%)', padding: '24px 16px', overflowY: 'auto' }}>
      {/* Background glow */}
      <div style={{ position: 'fixed', top: '15%', left: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(79,70,229,0.12) 0%, transparent 70%)`, pointerEvents: 'none' }} />
      <div style={{ position: 'fixed', bottom: '15%', right: '5%', width: 400, height: 400, background: `radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%)`, pointerEvents: 'none' }} />

      {/* Phone shell */}
      <div style={{
        width: 370,
        height: 800,
        background: 'linear-gradient(145deg, #E8E8ED 0%, #C8C8CF 60%, #B8B8BF 100%)',
        borderRadius: 48,
        padding: '11px 6px 9px',
        boxShadow: '0 0 0 1px rgba(255,255,255,0.8) inset, 0 0 0 1.5px #9C9CA8, 0 24px 64px rgba(0,0,0,0.55), 0 8px 20px rgba(0,0,0,0.3)',
        position: 'relative',
        flexShrink: 0,
        transform: `scale(${scale})`,
        transformOrigin: 'top center',
        marginBottom: scale < 1 ? `${(800 * scale - 800)}px` : 0,
      }}>
        {/* Dynamic island / notch */}
        <div style={{ position: 'absolute', top: 13, left: '50%', transform: 'translateX(-50%)', width: 110, height: 28, background: '#1A1A1A', borderRadius: 50, zIndex: 30, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, pointerEvents: 'none' }}>
          <div style={{ width: 9, height: 9, borderRadius: '50%', background: '#2C2C2C' }} />
          <div style={{ width: 48, height: 9, borderRadius: 20, background: '#2C2C2C' }} />
        </div>
        {/* Side buttons */}
        <div style={{ position: 'absolute', right: -3, top: 122, width: 3, height: 54, background: '#A0A0A8', borderRadius: '0 2px 2px 0', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 102, width: 3, height: 32, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 146, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />
        <div style={{ position: 'absolute', left: -3, top: 214, width: 3, height: 56, background: '#A0A0A8', borderRadius: '2px 0 0 2px', pointerEvents: 'none' }} />

        {/* Screen */}
        <div style={{ width: '100%', height: '100%', borderRadius: 40, overflow: 'hidden', background: '#F1F5F9', display: 'flex', flexDirection: 'column' }}>
          <StatusBar dark={screen === 'login'} />

          {/* Animated content */}
          <SIR key={animKey} k={animKey}>
            {screen === 'login' && <LoginScreen onLogin={() => navigate('dashboard')} />}
            {screen === 'dashboard' && <DashboardScreen navigate={navigate} />}
            {screen === 'jadwal' && <JadwalScreen navigate={navigate} />}
            {screen === 'riwayat' && <RiwayatScreen />}
            {screen === 'profil' && <ProfilScreen navigate={navigate} />}
            {screen === 'editprofil' && <EditProfilScreen navigate={navigate} />}
            {screen === 'ubahpassword' && <UbahPasswordScreen navigate={navigate} />}
            {screen === 'pengaturan' && <PengaturanScreen navigate={navigate} />}
            {screen === 'bantuan' && <BantuanScreen navigate={navigate} />}
          </SIR>

          {showNav && <BottomNav tab={tab} onTab={handleTabChange} />}
        </div>
      </div>
    </div>
  )
}
