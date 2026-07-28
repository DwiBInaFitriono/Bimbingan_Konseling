<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Studi Kasus - {{ $case->student->full_name ?? 'Siswa' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #000;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        table.info-table td.label {
            width: 25%;
            font-weight: bold;
            color: #555;
        }
        table.info-table td.colon {
            width: 3%;
        }
        .content-box {
            border: 1px solid #ccc;
            padding: 12px;
            background-color: #fafafa;
            border-radius: 4px;
            min-height: 60px;
            margin-bottom: 15px;
            white-space: pre-wrap;
        }
        .signature-container {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-box .space {
            height: 70px;
        }
        .signature-box .name {
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            body {
                padding: 0;
                background-color: #fff;
            }
            .content-box {
                background-color: #fff;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <!-- Header/Kop Surat -->
    <div class="header">
        <h2>SISTEM INFORMASI BIMBINGAN KONSELING</h2>
        <h2>LAYANAN BIMBINGAN & KONSELING (BK)</h2>
        <p>Alamat Sekolah, No. Telp, Website Resmi Sekolah</p>
    </div>

    <!-- Judul Dokumen -->
    <div class="doc-title">LAPORAN STUDI KASUS & PENANGANAN SISWA</div>

    <!-- Informasi Siswa -->
    <div class="section-title">I. Identitas Siswa</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td>{{ $case->student->full_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td class="colon">:</td>
            <td>{{ $case->student->nis ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas & Angkatan</td>
            <td class="colon">:</td>
            <td>Kelas {{ $case->student->class?->grade ?? '-' }} - {{ $case->student->class?->school_class_name ?? 'Tanpa Kelas' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Orang Tua / Wali</td>
            <td class="colon">:</td>
            <td>{{ $case->student->parent?->parent_full_name ?? '-' }} ({{ ucfirst($case->student->parent?->relationship ?? 'Wali') }})</td>
        </tr>
        <tr>
            <td class="label">No. HP Orang Tua</td>
            <td class="colon">:</td>
            <td>{{ $case->student->parent?->phone_number ?? '-' }}</td>
        </tr>
    </table>

    <!-- Informasi Kasus -->
    <div class="section-title">II. Detail Catatan Kasus</div>
    <table class="info-table">
        <tr>
            <td class="label">Judul Masalah/Kasus</td>
            <td class="colon">:</td>
            <td><strong>{{ $case->case_title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Guru Pelapor (Pengajar)</td>
            <td class="colon">:</td>
            <td>{{ $case->reporter_teacher }}</td>
        </tr>
        @if($case->subject_name)
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td class="colon">:</td>
            <td>{{ $case->subject_name }}</td>
        </tr>
        @endif
        @if($case->time_of_occurrence)
        <tr>
            <td class="label">Jam Pelajaran / Waktu</td>
            <td class="colon">:</td>
            <td>{{ $case->time_of_occurrence }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Tanggal Kejadian / Laporan</td>
            <td class="colon">:</td>
            <td>{{ \Carbon\Carbon::parse($case->case_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kategori Kasus</td>
            <td class="colon">:</td>
            <td>{{ ucfirst($case->case_type) }}</td>
        </tr>
        <tr>
            <td class="label">Status Penanganan</td>
            <td class="colon">:</td>
            <td>{{ ucfirst($case->status) }}</td>
        </tr>
        <tr>
            <td class="label">Sanksi Poin Pelanggaran</td>
            <td class="colon">:</td>
            <td>
                @if($case->points_applied)
                    <strong style="color: #dc3545;">+{{ $case->points_sanction }} Poin Pelanggaran (Telah Diterapkan)</strong>
                @else
                    Tidak ada / Belum diproses
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">III. Keterangan / Kronologi Kejadian</div>
    <div class="content-box">{{ $case->case_description }}</div>

    <div class="section-title">IV. Tindakan Penanganan yang Telah Diambil</div>
    <div class="content-box">{{ $case->action_taken ?: 'Belum ada tindakan penanganan yang tercatat secara resmi.' }}</div>

    <div class="section-title">V. Rekomendasi / Tindak Lanjut</div>
    <div class="content-box">{{ $case->recommendation ?: 'Belum ada rekomendasi tindak lanjut yang tercatat.' }}</div>

    @if($case->evidence_file)
    <div class="section-title">VI. Lampiran Bukti Media</div>
    <div style="text-align: center; margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; background: #fff;">
        @if(preg_match('/\.(jpg|jpeg|png|webp)$/i', $case->evidence_file))
            <img src="{{ asset($case->evidence_file) }}" alt="Bukti Lampiran" style="max-width: 100%; max-height: 250px; object-fit: contain;">
        @else
            <div style="padding: 20px; background: #f0f0f0; border-radius: 4px; font-weight: bold; color: #555;">
                Berkas Lampiran Video (Tersedia online: {{ asset($case->evidence_file) }})
            </div>
        @endif
    </div>
    @endif

    <!-- Bagian Tanda Tangan -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p><strong>Orang Tua / Wali Siswa</strong></p>
            <div class="space"></div>
            <div class="name">(..................................................)</div>
        </div>
        <div class="signature-box">
            <p>&nbsp;</p>
            <p><strong>Siswa yang Bersangkutan</strong></p>
            <div class="space"></div>
            <div class="name">{{ $case->student->full_name ?? 'Nama Siswa' }}</div>
        </div>
        <div class="signature-box">
            <p>Dibuat Oleh,</p>
            <p><strong>Guru Bimbingan Konseling</strong></p>
            <div class="space"></div>
            <div class="name">{{ $case->handler?->name ?? 'Nama Guru BK' }}</div>
        </div>
    </div>

    <!-- Script to trigger print on load -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
