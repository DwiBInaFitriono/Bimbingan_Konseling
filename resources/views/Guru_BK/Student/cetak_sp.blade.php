<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peringatan - {{ $student->full_name }}</title>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 13pt; line-height: 1.6; color: #1a1a1a; padding: 20px; }
        .page { max-width: 210mm; margin: 0 auto; padding: 25mm 20mm; background: #fff; }

        /* Header */
        .letter-header { text-align: center; border-bottom: 3px double #1a1a1a; padding-bottom: 12px; margin-bottom: 20px; }
        .letter-header h2 { font-size: 16pt; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        .letter-header h3 { font-size: 13pt; font-weight: normal; }
        .letter-header p { font-size: 10pt; color: #444; }

        /* Body */
        .letter-title { text-align: center; margin: 24px 0; }
        .letter-title h3 { font-size: 14pt; text-decoration: underline; text-transform: uppercase; }
        .letter-title .sp-level { font-size: 12pt; font-weight: normal; margin-top: 4px; }

        .info-table { width: 100%; margin: 16px 0; }
        .info-table td { padding: 3px 8px; vertical-align: top; }
        .info-table .label { width: 160px; font-weight: bold; }
        .info-table .sep { width: 12px; text-align: center; }

        .content-section { margin: 20px 0; }
        .content-section h4 { font-size: 12pt; margin-bottom: 8px; text-decoration: underline; }
        .violation-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .violation-table th, .violation-table td { border: 1px solid #333; padding: 6px 10px; text-align: left; font-size: 11pt; }
        .violation-table th { background: #f0f0f0; font-weight: bold; text-align: center; }
        .violation-table td.center { text-align: center; }
        .total-row { font-weight: bold; background: #fafafa; }

        .warning-text { margin: 16px 0; text-align: justify; }

        /* Signature */
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-block { text-align: center; width: 45%; }
        .signature-block .line { margin-top: 60px; border-bottom: 1px solid #333; display: inline-block; min-width: 180px; }
        .signature-block .title { font-size: 10pt; color: #555; }

        .print-btn { position: fixed; bottom: 20px; right: 20px; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #fff; background: #4154f1; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 14px rgba(65,84,241,.3); z-index: 1000; }
        .print-btn:hover { background: #3040d0; }

        .status-badge { display: inline-block; padding: 2px 10px; border-radius: 4px; font-size: 10pt; font-weight: bold; color: #fff; }
        .badge-bahaya { background: #dc3545; }
        .badge-peringatan { background: #fd7e14; }
        .badge-aman { background: #198754; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        <i class="bi bi-printer"></i> Cetak Surat
    </button>

    <div class="page">
        {{-- Letter Header --}}
        <div class="letter-header">
            <h2>Sistem Informasi Bimbingan & Konseling</h2>
            <h3>Surat Peringatan Siswa</h3>
            <p>Dokumen ini dicetak secara otomatis oleh sistem pada {{ now()->translatedFormat('l, d F Y - H:i') }} WIB</p>
        </div>

        {{-- Letter Title --}}
        <div class="letter-title">
            @if($printType === 'expel')
                <h3>Surat Rekomendasi Penanganan Khusus</h3>
                <div class="sp-level">Poin Pelanggaran Telah Melampaui Batas</div>
            @else
                @php
                    $totalPoin = $student->total_points ?? $student->pointDatas->sum('point_number');
                    if ($totalPoin >= 75) {
                        $spLevel = 'SP-3 (Peringatan Keras)';
                    } elseif ($totalPoin >= 30) {
                        $spLevel = 'SP-2 (Peringatan Kedua)';
                    } else {
                        $spLevel = 'SP-1 (Peringatan Pertama)';
                    }
                @endphp
                <h3>Surat Peringatan</h3>
                <div class="sp-level">{{ $spLevel }}</div>
            @endif
        </div>

        {{-- Student Info --}}
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="sep">:</td>
                <td>{{ $student->full_name }}</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td class="sep">:</td>
                <td>{{ $student->nis }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td class="sep">:</td>
                <td>{{ $student->class?->school_class_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Orang Tua/Wali</td>
                <td class="sep">:</td>
                <td>{{ $student->parent?->parent_full_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Total Poin Pelanggaran</td>
                <td class="sep">:</td>
                <td>
                    <strong>{{ $student->total_points ?? $student->pointDatas->sum('point_number') }}</strong>
                    @php $statusClass = match($student->status ?? 'aman') { 'bahaya' => 'badge-bahaya', 'peringatan' => 'badge-peringatan', default => 'badge-aman' }; @endphp
                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($student->status ?? 'aman') }}</span>
                </td>
            </tr>
        </table>

        {{-- Violation Details --}}
        <div class="content-section">
            <h4>Rincian Pelanggaran</h4>
            @if($student->pointDatas && $student->pointDatas->count() > 0)
                <table class="violation-table">
                    <thead>
                        <tr>
                            <th style="width: 40px">No</th>
                            <th>Pelanggaran</th>
                            <th style="width: 100px">Tanggal</th>
                            <th style="width: 60px">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->pointDatas as $i => $point)
                            <tr>
                                <td class="center">{{ $i + 1 }}</td>
                                <td>{{ $point->violation }}</td>
                                <td class="center">{{ $point->violation_date ? \Carbon\Carbon::parse($point->violation_date)->format('d/m/Y') : '-' }}</td>
                                <td class="center">{{ $point->point_number }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right; padding-right: 16px;">Total Poin</td>
                            <td class="center">{{ $student->pointDatas->sum('point_number') }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p><em>Belum ada rincian pelanggaran tercatat.</em></p>
            @endif
        </div>

        {{-- Warning Text --}}
        <div class="warning-text">
            @if($printType === 'expel')
                <p>Berdasarkan data pelanggaran di atas, siswa yang bersangkutan telah mengumpulkan poin pelanggaran yang melampaui batas toleransi yang ditetapkan oleh sekolah. Dengan ini, pihak Bimbingan Konseling merekomendasikan agar dilakukan penanganan khusus sesuai kebijakan sekolah.</p>
                <p style="margin-top: 8px">Orang tua/wali siswa dihimbau untuk hadir ke sekolah guna membahas langkah penanganan lanjutan.</p>
            @else
                <p>Surat peringatan ini diberikan kepada siswa yang bersangkutan karena telah melakukan pelanggaran tata tertib sekolah sebagaimana tercantum di atas. Siswa diharapkan untuk memperbaiki perilaku dan tidak mengulangi pelanggaran serupa.</p>
                <p style="margin-top: 8px">Apabila pelanggaran terus berlanjut, pihak sekolah akan mengambil tindakan lebih lanjut sesuai dengan peraturan yang berlaku.</p>
            @endif
        </div>

        {{-- Signature --}}
        <div class="signature-section">
            <div class="signature-block">
                <div class="title">Orang Tua / Wali</div>
                <div class="line"></div>
                <div>{{ $student->parent?->parent_full_name ?? '................................' }}</div>
            </div>
            <div class="signature-block">
                <div class="title">Guru BK</div>
                <div class="line"></div>
                <div>{{ auth()->user()?->name ?? '................................' }}</div>
                @if(auth()->user()?->nip)
                    <div style="font-size: 10pt; color: #555;">NIP. {{ auth()->user()->nip }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
