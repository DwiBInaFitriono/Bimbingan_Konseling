import React, { useState } from 'react';
import { Screen } from '../types';
import {
  COLOR_PRIMARY,
  COLOR_VIOLET,
  COLOR_SUCCESS,
  COLOR_WARNING,
  COLOR_DANGER,
} from '../constants';
import { FadeUpAnimation } from '../components/Animations';
import { SubHeader } from '../components/SubHeader';
import { ApiService } from '../services/api';

export function UbahPasswordScreen({ navigate }: { navigate: (targetScreen: Screen) => void }) {
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmNewPassword, setConfirmNewPassword] = useState('');
  const [isChangingPassword, setIsChangingPassword] = useState(false);
  const [isPasswordChangeCompleted, setIsPasswordChangeCompleted] = useState(false);
  const [feedbackToast, setFeedbackToast] = useState<{ messageText: string; isSuccess: boolean } | null>(null);

  const displayFeedbackToast = (messageText: string, isSuccess = false) => {
    setFeedbackToast({ messageText, isSuccess });
    setTimeout(() => setFeedbackToast(null), 4000);
  };

  const passwordStrengthLevel =
    newPassword.length === 0 ? 0 : newPassword.length < 6 ? 1 : newPassword.length < 10 ? 2 : 3;
  const passwordStrengthLabels = ['', 'Lemah', 'Sedang', 'Kuat'];
  const passwordStrengthColors = ['', COLOR_DANGER, COLOR_WARNING, COLOR_SUCCESS];

  const PasswordInputField = ({
    inputLabel,
    inputValue,
    onInputChange,
    inputPlaceholder,
  }: {
    inputLabel: string;
    inputValue: string;
    onInputChange: (updatedValue: string) => void;
    inputPlaceholder: string;
  }) => {
    const [isPasswordRevealed, setIsPasswordRevealed] = useState(false);
    const [isInputFocused, setIsInputFocused] = useState(false);

    return (
      <div>
        <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6, textTransform: 'uppercase', letterSpacing: '0.04em' }}>
          {inputLabel} <span style={{ color: COLOR_DANGER }}>*</span>
        </label>
        <div style={{ position: 'relative' }}>
          <div style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: isInputFocused ? COLOR_VIOLET : '#94A3B8', transition: 'color 0.2s' }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          </div>
          <input
            type={isPasswordRevealed ? 'text' : 'password'}
            value={inputValue}
            onChange={changeEvent => onInputChange(changeEvent.target.value)}
            placeholder={inputPlaceholder}
            onFocus={() => setIsInputFocused(true)}
            onBlur={() => setIsInputFocused(false)}
            style={{ width: '100%', paddingLeft: 42, paddingRight: 44, paddingTop: 13, paddingBottom: 13, borderRadius: 14, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${isInputFocused ? COLOR_VIOLET : '#E2E8F0'}`, outline: 'none', transition: 'border-color 0.2s', boxSizing: 'border-box' }}
          />
          <button
            onClick={() => setIsPasswordRevealed(currentVisibility => !currentVisibility)}
            style={{ position: 'absolute', right: 14, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', padding: 0, display: 'flex' }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
              {isPasswordRevealed ? <><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/></> : <><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></>}
            </svg>
          </button>
        </div>
      </div>
    );
  };

  const handleChangePasswordSubmit = async () => {
    if (!currentPassword) {
      displayFeedbackToast('Masukkan password saat ini');
      return;
    }
    if (!newPassword) {
      displayFeedbackToast('Masukkan password baru');
      return;
    }
    if (newPassword.length < 6) {
      displayFeedbackToast('Password baru minimal 6 karakter');
      return;
    }
    if (newPassword !== confirmNewPassword) {
      displayFeedbackToast('Konfirmasi password tidak cocok');
      return;
    }
    setIsChangingPassword(true);
    try {
      const studentStorageData = localStorage.getItem('student_data');
      if (!studentStorageData) {
        displayFeedbackToast('Sesi login tidak ditemukan. Silakan login kembali.');
        setIsChangingPassword(false);
        return;
      }
      const changePasswordResponse = await ApiService.ubahPassword(currentPassword, newPassword);
      if (changePasswordResponse.success) {
        setIsPasswordChangeCompleted(true);
        setTimeout(() => navigate('profil'), 2500);
      } else {
        displayFeedbackToast(changePasswordResponse.message || 'Gagal mengubah password');
      }
    } catch (passwordError: any) {
      displayFeedbackToast(passwordError.message || 'Terjadi kesalahan');
    } finally {
      setIsChangingPassword(false);
    }
  };

  if (isPasswordChangeCompleted) {
    return (
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#F1F5F9', padding: '0 32px', animation: 'bounceIn 0.5s ease both' }}>
        <div style={{ width: 80, height: 80, borderRadius: '50%', background: `linear-gradient(135deg, ${COLOR_VIOLET} 0%, ${COLOR_PRIMARY} 100%)`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20, boxShadow: `0 12px 32px rgba(124,58,237,0.45)` }}>
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 20, color: '#1E293B', textAlign: 'center', margin: '0 0 8px' }}>Password Diubah!</h2>
        <p style={{ color: '#64748B', fontSize: 13, textAlign: 'center' }}>Password akun Anda berhasil diperbarui. Gunakan password baru untuk login berikutnya.</p>
      </div>
    );
  }

  return (
    <>
      {feedbackToast && (
        <div style={{ position: 'fixed', top: 16, left: '50%', transform: 'translateX(-50%)', zIndex: 9999, maxWidth: 320, width: 'calc(100% - 32px)', padding: '12px 16px', borderRadius: 14, background: feedbackToast.isSuccess ? '#ECFDF5' : '#FFF1F2', border: `1.5px solid ${feedbackToast.isSuccess ? '#6EE7B7' : '#FCA5A5'}`, boxShadow: '0 8px 24px rgba(0,0,0,0.12)', display: 'flex', alignItems: 'center', gap: 10, animation: 'fadeUp 0.3s ease both' }}>
          <div style={{ width: 28, height: 28, borderRadius: 8, background: feedbackToast.isSuccess ? '#10B981' : '#EF4444', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
              {feedbackToast.isSuccess ? <path d="M20 6L9 17l-5-5"/> : <><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></>}
            </svg>
          </div>
          <p style={{ margin: 0, fontSize: 13, color: feedbackToast.isSuccess ? '#065F46' : '#9B1C1C', fontFamily: 'Inter', lineHeight: 1.5 }}>{feedbackToast.messageText}</p>
        </div>
      )}
      <SubHeader title="Ubah Password" subtitle="Keamanan & privasi akun" onBackPress={() => navigate('profil')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 20, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
              <PasswordInputField inputLabel="Password Saat Ini" inputValue={currentPassword} onInputChange={setCurrentPassword} inputPlaceholder="Masukkan password saat ini" />
              <PasswordInputField inputLabel="Password Baru" inputValue={newPassword} onInputChange={setNewPassword} inputPlaceholder="Min. 6 karakter" />
              {newPassword.length > 0 && (
                <div>
                  <div style={{ display: 'flex', gap: 4, marginBottom: 4 }}>
                    {[1, 2, 3].map(strengthStepIndex => (
                      <div key={strengthStepIndex} style={{ flex: 1, height: 4, borderRadius: 4, background: strengthStepIndex <= passwordStrengthLevel ? passwordStrengthColors[passwordStrengthLevel] : '#E2E8F0', transition: 'all 0.3s' }} />
                    ))}
                  </div>
                  <p style={{ fontSize: 11, color: passwordStrengthColors[passwordStrengthLevel], fontWeight: 700, margin: 0 }}>Kekuatan: {passwordStrengthLabels[passwordStrengthLevel]}</p>
                </div>
              )}
              <PasswordInputField inputLabel="Konfirmasi Password" inputValue={confirmNewPassword} onInputChange={setConfirmNewPassword} inputPlaceholder="Ulangi password baru" />
              {confirmNewPassword.length > 0 && newPassword !== confirmNewPassword && (
                <p style={{ fontSize: 12, color: COLOR_DANGER, fontWeight: 600, margin: '-8px 0 0', display: 'flex', alignItems: 'center', gap: 6 }}>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_DANGER} strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  Password tidak cocok
                </p>
              )}
            </div>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={100}>
          <div style={{ background: '#FFF5F5', border: '1px solid #FED7D7', borderRadius: 16, padding: '14px 16px', display: 'flex', gap: 12, alignItems: 'flex-start' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={COLOR_DANGER} strokeWidth="2" strokeLinecap="round" style={{ marginTop: 1, flexShrink: 0 }}><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <p style={{ fontSize: 12, color: '#9B1C1C', margin: 0, lineHeight: 1.6 }}>Jangan bagikan password kepada siapapun. Password yang kuat menggunakan kombinasi huruf, angka, dan simbol.</p>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={150}>
          <button
            onClick={handleChangePasswordSubmit}
            disabled={isChangingPassword || !currentPassword || !newPassword || newPassword !== confirmNewPassword || newPassword.length < 6}
            style={{
              width: '100%', padding: '16px', borderRadius: 18, border: 'none',
              cursor: (isChangingPassword || !currentPassword || !newPassword || newPassword !== confirmNewPassword || newPassword.length < 6) ? 'not-allowed' : 'pointer',
              background: (isChangingPassword || !currentPassword || !newPassword || newPassword !== confirmNewPassword || newPassword.length < 6) ? '#CBD5E1' : `linear-gradient(135deg, ${COLOR_VIOLET} 0%, ${COLOR_PRIMARY} 100%)`,
              boxShadow: (!isChangingPassword && currentPassword && newPassword && newPassword === confirmNewPassword && newPassword.length >= 6) ? `0 10px 28px rgba(124,58,237,0.4)` : 'none',
              color: '#fff', fontWeight: 900, fontSize: 16, fontFamily: 'Nunito',
              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, transition: 'all 0.25s',
            }}
          >
            {isChangingPassword ? (
              <><svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg> Memproses...</>
            ) : (
              <><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg> Ubah Password</>
            )}
          </button>
        </FadeUpAnimation>
      </div>
    </>
  );
}
