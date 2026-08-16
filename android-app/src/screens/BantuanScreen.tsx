import React, { useState } from 'react';
import { Screen, HistTab } from '../types';
import { P, V, T, AM, RD, IND } from '../constants';
import { FU, SIR } from '../components/Animations';
import { StatusBar } from '../components/StatusBar';
import { BottomNav } from '../components/BottomNav';
import { SubHeader } from '../components/SubHeader';


export function BantuanScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [openFaq, setOpenFaq] = useState<number | null>(null)
  const faqs = [
    { q: 'Bagaimana cara mengajukan konseling?', a: 'Buka halaman Jadwal dari menu bawah, isi form pengajuan dengan memilih tanggal, guru BK, sesi waktu, dan topik yang ingin didiskusikan, kemudian klik Kirim Pengajuan.' },
    { q: 'Bagaimana cara mengubah password akun saya?', a: 'Buka halaman Profil, pilih menu Ubah Password, masukkan password lama dan password baru minimal 6 karakter, lalu konfirmasi.' },
    { q: 'Bagaimana cara membuat laporan studi kasus?', a: 'Laporan studi kasus hanya dapat dibuat oleh Guru BK. Hubungi guru BK Anda untuk membuat laporan kasus yang diperlukan.' },
    { q: 'Bagaimana cara mengelola data poin siswa?', a: 'Data poin dikelola oleh administrator dan guru BK. Anda dapat melihat riwayat poin di halaman Dashboard pada tab Pelanggaran.' },
    { q: 'Bagaimana cara mencatat prestasi siswa?', a: 'Prestasi dicatat oleh Guru BK atau Wali Kelas. Informasikan prestasi Anda kepada guru terkait untuk dicatat ke dalam sistem.' },
  ]

  return (
    <>
      <SubHeader title="Pusat Bantuan" sub="FAQ, panduan & kontak admin" onBack={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>

        {/* Quick links */}
        <FU d={0}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            {[
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2" strokeLinecap="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>, label: 'Panduan Penggunaan', sub: 'Cara pakai fitur-fitur aplikasi', c: P, bg: '#EEF2FF' },
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>, label: 'Hubungi Admin', sub: 'Kirim pesan ke pengelola sistem', c: T, bg: '#F0FDF4' },
              { icon: <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={AM} strokeWidth="2" strokeLinecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>, label: 'Tips & Trik', sub: 'Kiat sukses belajar dan berprestasi', c: AM, bg: '#FFFBEB' },
            ].map((item, i, arr) => (
              <button
                key={i}
                style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 14, padding: '14px 16px', background: 'none', border: 'none', borderBottom: i < arr.length - 1 ? '1px solid #F8FAFC' : 'none', cursor: 'pointer', textAlign: 'left', transition: 'background 0.15s' }}
                onMouseDown={e => (e.currentTarget.style.background = '#F8FAFC')}
                onMouseUp={e => (e.currentTarget.style.background = 'none')}
                onMouseLeave={e => (e.currentTarget.style.background = 'none')}
              >
                <div style={{ width: 38, height: 38, borderRadius: 12, background: item.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>{item.icon}</div>
                <div style={{ flex: 1 }}>
                  <p style={{ fontSize: 13, fontWeight: 800, color: '#1E293B', margin: 0, fontFamily: 'Nunito' }}>{item.label}</p>
                  <p style={{ fontSize: 11, color: '#94A3B8', margin: '2px 0 0' }}>{item.sub}</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="2.5" strokeLinecap="round"><path d="M9 18l6-6-6-6"/></svg>
              </button>
            ))}
          </div>
        </FU>

        {/* FAQ */}
        <FU d={80}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 18px', borderBottom: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 8 }}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 14, color: '#1E293B', margin: 0 }}>Pertanyaan yang Sering Diajukan</p>
            </div>
            {faqs.map((faq, i) => (
              <div key={i} style={{ borderBottom: i < faqs.length - 1 ? '1px solid #F8FAFC' : 'none' }}>
                <button
                  onClick={() => setOpenFaq(openFaq === i ? null : i)}
                  style={{ width: '100%', padding: '14px 18px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, background: 'none', border: 'none', cursor: 'pointer', textAlign: 'left' }}
                >
                  <span style={{ fontSize: 13, fontWeight: 700, color: '#1E293B', fontFamily: 'Nunito', lineHeight: 1.4 }}>{faq.q}</span>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" strokeWidth="2.5" strokeLinecap="round" style={{ flexShrink: 0, transition: 'transform 0.3s', transform: openFaq === i ? 'rotate(180deg)' : 'none' }}><path d="M6 9l6 6 6-6"/></svg>
                </button>
                {openFaq === i && (
                  <div style={{ padding: '0 18px 14px', animation: 'fadeUp 0.25s ease both' }}>
                    <p style={{ fontSize: 13, color: '#64748B', lineHeight: 1.6, margin: 0 }}>{faq.a}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </FU>

        {/* Contact */}
        <FU d={160}>
          <div style={{ background: '#fff', borderRadius: 20, overflow: 'hidden', boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ padding: '14px 16px', borderBottom: '1px solid #F1F5F9', display: 'flex', alignItems: 'center', gap: 8 }}>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2.5" strokeLinecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.7A2 2 0 012 .18h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
              <p style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 14, color: '#1E293B', margin: 0 }}>Hubungi Kami</p>
            </div>
            {[
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2" strokeLinecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>, label: 'Email', value: 'admin@sistembk.sch.id', c: P, bg: '#EEF2FF' },
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={T} strokeWidth="2" strokeLinecap="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>, label: 'WhatsApp', value: '+62 812-3456-7890', c: T, bg: '#F0FDF4' },
              { icon: <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke={RD} strokeWidth="2" strokeLinecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>, label: 'Lokasi', value: 'Ruang BK Lantai 2', c: RD, bg: '#FFF1F2' },
            ].map((item, i, arr) => (
              <button
                key={i}
                style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 14, padding: '13px 16px', background: 'none', border: 'none', borderBottom: i < arr.length - 1 ? '1px solid #F8FAFC' : 'none', cursor: 'pointer', textAlign: 'left', transition: 'background 0.15s' }}
                onMouseDown={e => (e.currentTarget.style.background = '#F8FAFC')}
                onMouseUp={e => (e.currentTarget.style.background = 'none')}
                onMouseLeave={e => (e.currentTarget.style.background = 'none')}
              >
                <div style={{ width: 36, height: 36, borderRadius: 11, background: item.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>{item.icon}</div>
                <div style={{ flex: 1 }}>
                  <p style={{ fontSize: 11, fontWeight: 700, color: '#94A3B8', margin: 0, textTransform: 'uppercase', letterSpacing: '0.04em' }}>{item.label}</p>
                  <p style={{ fontSize: 13, fontWeight: 700, color: '#1E293B', margin: '2px 0 0', fontFamily: 'Nunito' }}>{item.value}</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" strokeWidth="2.5" strokeLinecap="round"><path d="M9 18l6-6-6-6"/></svg>
              </button>
            ))}
          </div>
        </FU>
      </div>
    </>
  )
}

// ─── MAIN APP ─────────────────────────────────────────────────────────────────
