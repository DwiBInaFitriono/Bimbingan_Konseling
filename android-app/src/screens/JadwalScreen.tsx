import React, { useState } from 'react';
import { ApiService } from '../services/api';
import { Screen } from '../types';
import {
  COLOR_PRIMARY,
  COLOR_VIOLET,
  COLOR_SUCCESS,
  COLOR_DANGER,
  COLOR_INDIGO,
} from '../constants';
import { FadeUpAnimation } from '../components/Animations';
import { SubHeader } from '../components/SubHeader';

export function JadwalScreen({ navigate }: { navigate: (targetScreen: Screen) => void }) {
  const [counselingType, setCounselingType] = useState<'individu' | 'kelompok'>('individu');
  const [counselingDate, setCounselingDate] = useState('');
  const [counselingSessionTime, setCounselingSessionTime] = useState('');
  const [counselingTopic, setCounselingTopic] = useState('');
  const [counselingDescription, setCounselingDescription] = useState('');
  const [isSubmittingSchedule, setIsSubmittingSchedule] = useState(false);
  const [isScheduleSubmitted, setIsScheduleSubmitted] = useState(false);
  const [feedbackToast, setFeedbackToast] = useState<{ messageText: string; isSuccess: boolean } | null>(null);

  const displayFeedbackToast = (messageText: string, isSuccess = false) => {
    setFeedbackToast({ messageText, isSuccess });
    setTimeout(() => setFeedbackToast(null), 3500);
  };

  const availableSessionOptions = [
    '07:00 – 08:00',
    '08:00 – 09:00',
    '09:00 – 10:00',
    '10:00 – 11:00',
    '13:00 – 14:00',
  ];

  const handleScheduleSubmission = async () => {
    if (!counselingDate || !counselingSessionTime || !counselingTopic) {
      displayFeedbackToast('Mohon lengkapi tanggal, sesi, dan topik konseling.');
      return;
    }
    setIsSubmittingSchedule(true);

    const studentSessionData = localStorage.getItem('student_data');
    if (!studentSessionData) {
      displayFeedbackToast('Sesi login tidak ditemukan. Silakan login kembali.');
      setIsSubmittingSchedule(false);
      return;
    }

    try {
      const apiScheduleResponse = await ApiService.postJadwal({
        type: counselingType,
        requested_date: counselingDate,
        requested_time: counselingSessionTime.split(' ')[0],
        topic: counselingTopic,
        description: counselingDescription || undefined,
      });

      if (apiScheduleResponse.success) {
        setIsScheduleSubmitted(true);
        setTimeout(() => {
          navigate('dashboard');
          setIsScheduleSubmitted(false);
        }, 2200);
      } else {
        displayFeedbackToast('Gagal mengajukan jadwal: ' + apiScheduleResponse.message);
      }
    } catch (submissionError: any) {
      displayFeedbackToast('Error: ' + submissionError.message);
    } finally {
      setIsSubmittingSchedule(false);
    }
  };

  if (isScheduleSubmitted) {
    return (
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#F1F5F9', padding: '0 32px', animation: 'bounceIn 0.55s ease both' }}>
        <div style={{ width: 88, height: 88, borderRadius: '50%', background: `linear-gradient(135deg, ${COLOR_SUCCESS} 0%, #059669 100%)`, boxShadow: `0 16px 40px rgba(16,185,129,0.5)`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 20 }}>
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round" style={{ animation: 'fadeIn 0.4s ease 0.3s both' }}>
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </div>
        <h2 style={{ fontFamily: 'Nunito', fontWeight: 900, fontSize: 22, color: '#1E293B', textAlign: 'center', margin: '0 0 8px' }}>Pengajuan Terkirim!</h2>
        <p style={{ color: '#64748B', fontSize: 14, textAlign: 'center', margin: 0, lineHeight: 1.6 }}>Jadwal konseling Anda sedang diproses oleh guru BK. Kami akan memberi tahu Anda segera.</p>
        <div style={{ marginTop: 24, display: 'flex', gap: 4 }}>
          {[0, 1, 2].map(dotIndex => (
            <div key={dotIndex} style={{ width: 8, height: 8, borderRadius: '50%', background: COLOR_SUCCESS, animation: `pulseDot 1.2s ease-in-out ${dotIndex * 200}ms infinite` }} />
          ))}
        </div>
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
      <SubHeader title="Pengajuan Jadwal" subtitle="Layanan Bimbingan Konseling" onBackPress={() => navigate('dashboard')} />
      <div style={{ flex: 1, overflowY: 'auto', background: '#F1F5F9', padding: '16px 16px 24px', display: 'flex', flexDirection: 'column', gap: 14 }}>
        <FadeUpAnimation delayMilliseconds={0}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2.5" strokeLinecap="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Jadwal Pertemuan</span>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>
                  Tanggal Pertemuan <span style={{ color: COLOR_DANGER }}>*</span>
                </label>
                <input
                  type="date"
                  min={new Date().toISOString().split('T')[0]}
                  value={counselingDate}
                  onChange={changeEvent => setCounselingDate(changeEvent.target.value)}
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 14, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${counselingDate ? COLOR_PRIMARY : '#E2E8F0'}`, outline: 'none', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Sesi Waktu <span style={{ color: COLOR_DANGER }}>*</span></label>
                <select
                  value={counselingSessionTime}
                  onChange={changeEvent => setCounselingSessionTime(changeEvent.target.value)}
                  style={{ width: '100%', padding: '12px 10px', borderRadius: 12, fontSize: 13, color: counselingSessionTime ? '#1E293B' : '#94A3B8', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${counselingSessionTime ? COLOR_PRIMARY : '#E2E8F0'}`, outline: 'none', appearance: 'none', boxSizing: 'border-box' }}
                >
                  <option value="">-- Pilih Sesi --</option>
                  {availableSessionOptions.map(sessionOption => <option key={sessionOption} value={sessionOption}>{sessionOption}</option>)}
                </select>
              </div>
            </div>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={80}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2.5" strokeLinecap="round"><circle cx="17" cy="21" r="1"/><circle cx="9" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57L23 6H6"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Tipe Konseling</span>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
              {([
                { optionId: 'individu', optionLabel: 'Individu', optionSubtitle: 'Sesi pribadi 1 lawan 1', optionIcon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> },
                { optionId: 'kelompok', optionLabel: 'Kelompok', optionSubtitle: 'Ajak teman bergabung', optionIcon: <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"><circle cx="9" cy="7" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M1 20c0-3.3 3.1-6 7-6M13 18c0-2.8 2.7-5 6-5"/></svg> },
              ] as { optionId: string; optionLabel: string; optionSubtitle: string; optionIcon: React.ReactNode }[]).map(({ optionId, optionLabel, optionSubtitle, optionIcon }) => (
                <button
                  key={optionId}
                  onClick={() => setCounselingType(optionId as 'individu' | 'kelompok')}
                  style={{
                    padding: '14px 12px',
                    borderRadius: 14,
                    border: `2px solid ${counselingType === optionId ? COLOR_PRIMARY : '#E2E8F0'}`,
                    background: counselingType === optionId ? '#EEF2FF' : '#F8FAFC',
                    cursor: 'pointer',
                    textAlign: 'left',
                    transition: 'all 0.2s',
                  }}
                >
                  <div style={{ color: counselingType === optionId ? COLOR_PRIMARY : '#CBD5E1', marginBottom: 8 }}>{optionIcon}</div>
                  <p style={{ fontFamily: 'Nunito', fontWeight: 800, fontSize: 13, color: counselingType === optionId ? COLOR_PRIMARY : '#475569', margin: '0 0 3px' }}>{optionLabel}</p>
                  <p style={{ fontSize: 11, color: counselingType === optionId ? COLOR_INDIGO : '#94A3B8', margin: 0, lineHeight: 1.4 }}>{optionSubtitle}</p>
                </button>
              ))}
            </div>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={160}>
          <div style={{ background: '#fff', borderRadius: 20, padding: 18, boxShadow: '0 2px 12px rgba(0,0,0,0.05)' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, paddingBottom: 12, borderBottom: '1px solid #F1F5F9' }}>
              <div style={{ width: 28, height: 28, borderRadius: 10, background: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke={COLOR_PRIMARY} strokeWidth="2.5" strokeLinecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
              </div>
              <span style={{ fontSize: 12, fontWeight: 800, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.06em', fontFamily: 'Nunito' }}>Detail Konsultasi</span>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Topik Bahasan <span style={{ color: COLOR_DANGER }}>*</span></label>
                <input
                  type="text"
                  value={counselingTopic}
                  onChange={changeEvent => setCounselingTopic(changeEvent.target.value)}
                  placeholder="Contoh: Kendala belajar, konsultasi karir..."
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: `1.5px solid ${counselingTopic ? COLOR_PRIMARY : '#E2E8F0'}`, outline: 'none', boxSizing: 'border-box' }}
                  onFocus={focusEvent => focusEvent.target.style.borderColor = COLOR_PRIMARY}
                  onBlur={blurEvent => blurEvent.target.style.borderColor = counselingTopic ? COLOR_PRIMARY : '#E2E8F0'}
                />
              </div>
              <div>
                <label style={{ fontSize: 11, fontWeight: 700, color: '#64748B', display: 'block', marginBottom: 6 }}>Deskripsi Singkat <span style={{ color: '#CBD5E1' }}>(opsional)</span></label>
                <textarea
                  value={counselingDescription}
                  maxLength={500}
                  onChange={changeEvent => setCounselingDescription(changeEvent.target.value)}
                  placeholder="Ceritakan singkat hal yang ingin didiskusikan... (Kerahasiaan data Anda terlindungi)"
                  rows={4}
                  style={{ width: '100%', padding: '12px 14px', borderRadius: 12, fontSize: 13, color: '#1E293B', fontFamily: 'Inter', background: '#F8FAFC', border: '1.5px solid #E2E8F0', outline: 'none', resize: 'none', boxSizing: 'border-box', lineHeight: 1.6 }}
                  onFocus={focusEvent => focusEvent.target.style.borderColor = COLOR_PRIMARY}
                  onBlur={blurEvent => blurEvent.target.style.borderColor = '#E2E8F0'}
                />
                <p style={{ textAlign: 'right', fontSize: 11, color: '#CBD5E1', margin: '4px 0 0' }}>{counselingDescription.length}/500</p>
              </div>
            </div>
          </div>
        </FadeUpAnimation>

        <FadeUpAnimation delayMilliseconds={220}>
          <button
            onClick={handleScheduleSubmission}
            disabled={isSubmittingSchedule || !counselingDate || !counselingSessionTime || !counselingTopic}
            style={{
              width: '100%',
              padding: '16px',
              borderRadius: 18,
              border: 'none',
              cursor: (isSubmittingSchedule || !counselingDate || !counselingSessionTime || !counselingTopic) ? 'not-allowed' : 'pointer',
              background: (isSubmittingSchedule || !counselingDate || !counselingSessionTime || !counselingTopic) ? '#CBD5E1' : `linear-gradient(135deg, ${COLOR_PRIMARY} 0%, ${COLOR_VIOLET} 100%)`,
              boxShadow: (!isSubmittingSchedule && counselingDate && counselingSessionTime && counselingTopic) ? `0 10px 28px rgba(79,70,229,0.4)` : 'none',
              color: '#fff',
              fontWeight: 900,
              fontSize: 16,
              fontFamily: 'Nunito',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              transition: 'all 0.25s',
            }}
          >
            {isSubmittingSchedule ? (
              <>
                <svg className="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0" strokeDasharray="28" strokeDashoffset="6"/></svg>
                Mengirim Pengajuan...
              </>
            ) : (
              <>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                Kirim Pengajuan
              </>
            )}
          </button>
        </FadeUpAnimation>
      </div>
    </>
  );
}
