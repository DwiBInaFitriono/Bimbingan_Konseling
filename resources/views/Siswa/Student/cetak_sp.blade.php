<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Peringatan Poin - {{ $student->full_name }}</title>
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
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 25px;
            color: #555;
        }
        .section-title {
            font-size: 13px;
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
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        table.data-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            font-weight: bold;
            text-align: left;
        }
        table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .alert-box {
            border: 1px solid #f5c2c7;
            background-color: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .alert-box.warning {
            border: 1px solid #ffe69c;
            background-color: #fff3cd;
            color: #664d03;
        }
        .signature-container {
            margin-top: 40px;
            width: 100%;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-box .space {
            height: 65px;
        }
        .signature-box .name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-box .role {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }
        @media print {
            body {
                padding: 0;
                background-color: #fff;
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

    <!-- Judul Dokumen & Status SP -->
    @if($printType === 'expel')
        <div class="doc-title" style="color: #842029;">SURAT KEPUTUSAN PENGEMBALIAN SISWA KEPADA ORANG TUA/WALI</div>
        <div class="doc-title" style="color: #842029; font-size: 13px; margin-top: 5px;">(DIKELUARKAN DARI SEKOLAH)</div>
        <div class="doc-subtitle">Nomor: SK-OUT/{{ date('m/Y') }}/BK-{{ $student->nis }}</div>
        <div class="alert-box">
            TINDAKAN AKHIR: SISWA DIKEMBALIKAN KEPADA ORANG TUA/WALI (DIKELUARKAN) KARENA TELAH MELAMPAUI BATAS AKUMULASI POIN KEDISIPLINAN DENGAN TOTAL {{ $student->total_points }} POIN
        </div>
    @elseif($student->status === 'bahaya')
        <div class="doc-title" style="color: #dc3545;">SURAT PERINGATAN 2 (SP 2 / BERAT)</div>
        <div class="doc-subtitle">Nomor: SP-2/{{ date('m/Y') }}/BK-{{ $student->nis }}</div>
        <div class="alert-box">
            STATUS: BAHAYA - SISWA DIKENAKAN SURAT PERINGATAN 2 (SP 2) DENGAN TOTAL {{ $student->total_points }} POIN PELANGGARAN
        </div>
    @elseif($student->status === 'peringatan')
        <div class="doc-title" style="color: #fd7e14;">SURAT PERINGATAN 1 (SP 1)</div>
        <div class="doc-subtitle">Nomor: SP-1/{{ date('m/Y') }}/BK-{{ $student->nis }}</div>
        <div class="alert-box warning">
            STATUS: PERINGATAN - SISWA DIKENAKAN SURAT PERINGATAN 1 (SP 1) DENGAN TOTAL {{ $student->total_points }} POIN PELANGGARAN
        </div>
    @else
        <div class="doc-title">LAPORAN AKUMULASI POIN PELANGGARAN SISWA</div>
        <div class="doc-subtitle">Tanggal Cetak: {{ date('d F Y') }}</div>
    @endif

    <!-- Informasi Siswa -->
    <div class="section-title">I. Identitas Siswa</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td><strong>{{ $student->full_name }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td class="colon">:</td>
            <td>{{ $student->nis }}</td>
        </tr>
        <tr>
            <td class="label">Kelas & Rombel</td>
            <td class="colon">:</td>
            <td>Kelas {{ $student->class?->grade ?? '-' }} - {{ $student->class?->school_class_name ?? 'Tanpa Kelas' }}</td>
        </tr>
        <tr>
            <td class="label">Jurusan / Kompetensi</td>
            <td class="colon">:</td>
            <td>{{ $student->class?->school_class_major ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Orang Tua / Wali</td>
            <td class="colon">:</td>
            <td>{{ $student->parent?->parent_full_name ?? '-' }} ({{ ucfirst($student->parent?->relationship ?? 'Wali') }})</td>
        </tr>
        <tr>
            <td class="label">No. HP Orang Tua</td>
            <td class="colon">:</td>
            <td>{{ $student->parent?->phone_number ?? '-' }}</td>
        </tr>
    </table>

    <!-- Detail Riwayat Pelanggaran -->
    <div class="section-title">II. Riwayat Catatan Pelanggaran Poin</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="45%">Pelanggaran / Masalah</th>
                <th width="20%">Keterangan Tambahan</th>
                <th width="15%">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($student->pointDatas as $index => $violation)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($violation->violation_date)->format('d/m/Y') }}</td>
                    <td>{{ $violation->violation }}</td>
                    <td>{{ $violation->description ?: '-' }}</td>
                    <td style="font-weight: bold; color: #dc3545; text-align: center;">+{{ $violation->point_number }} Poin</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #777;">
                        Belum ada catatan pelanggaran poin untuk siswa ini.
                    </td>
                </tr>
            @endforelse
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" style="text-align: right; padding-right: 15px;">TOTAL AKUMULASI POIN:</td>
                <td style="text-align: center; font-size: 14px; color: #dc3545;">{{ $student->total_points }} Poin</td>
            </tr>
        </tbody>
    </table>

    <!-- Keterangan & Tindak Lanjut Sekolah -->
    <div class="section-title">III. Keterangan & Tindak Lanjut Sekolah</div>
    <div style="border: 1px solid #ccc; padding: 12px; background-color: #fafafa; border-radius: 4px; margin-bottom: 25px; line-height: 1.6; text-align: justify;">
        @if($printType === 'expel')
            Berdasarkan akumulasi catatan pelanggaran tata tertib dan kedisiplinan sekolah di atas, di mana siswa bersangkutan telah melampaui batas akumulasi poin pelanggaran maksimal yang diperbolehkan oleh aturan sekolah, maka dengan ini pihak sekolah memutuskan untuk <strong>Mengembalikan Siswa Tersebut Kepada Orang Tua / Wali (Dikeluarkan dari Sekolah)</strong> terhitung sejak surat keputusan ini diterbitkan.
        @elseif($student->status === 'bahaya')
            Mengingat akumulasi poin pelanggaran siswa telah mencapai ambang batas Surat Peringatan 2 (SP 2 / Berat), maka siswa bersangkutan dikenakan sanksi kedisiplinan berupa <strong>Skorsing Pembinaan selama ..... hari</strong> terhitung sejak tanggal surat ini diterbitkan. Jika selama masa skorsing atau sesudahnya siswa masih melakukan pelanggaran disiplin kembali, maka sekolah akan mengeluarkan <strong>Surat Peringatan Terakhir berupa pengembalian siswa kepada Orang Tua/Wali (Dikeluarkan dari Sekolah)</strong> secara mutlak.
        @elseif($student->status === 'peringatan')
            Sehubungan dengan total poin pelanggaran siswa yang telah mencapai batas Surat Peringatan 1 (SP 1), maka diterbitkan surat peringatan pertama ini. <strong>Pesan & Saran Pembinaan BK:</strong> Siswa diwajibkan untuk menjaga sikap dan tingkah laku, berjanji untuk tidak mengulangi tindakan indisipliner, serta bersedia mematuhi seluruh peraturan tata tertib sekolah dengan tertib dan sadar demi kelancaran proses pembelajaran siswa yang bersangkutan.
        @else
            Laporan ini dibuat sebagai berkas monitoring evaluasi kedisiplinan siswa di sekolah. Siswa diharapkan tetap mempertahankan kedisiplinan dan menjaga ketertiban selama menempuh pendidikan di sekolah.
        @endif
    </div>

    <!-- Bagian Tanda Tangan Dinamis -->
    <div class="signature-container">
        @if($student->status === 'bahaya' || $printType === 'expel')
            {{-- SP Berat/Keluar: Tanda Tangan Wali Kelas, Guru BK, Kepala Sekolah + Orang Tua (TIDAK ADA SISWA) --}}
            <div class="signature-row">
                <div class="signature-box" style="width: 45%;">
                    <p>Mengetahui,</p>
                    <p><strong>Orang Tua / Wali Siswa</strong></p>
                    <div class="space"></div>
                    <div class="name">(..................................................)</div>
                </div>
                <div class="signature-box" style="width: 45%;">
                    <p>Dibuat Oleh,</p>
                    <p><strong>Guru Bimbingan Konseling</strong></p>
                    <div class="space"></div>
                    <div class="name">{{ auth()->user()->name ?? 'Nama Guru BK' }}</div>
                    <div class="role">NIP. {{ auth()->user()->nip ?? '.....................................' }}</div>
                </div>
            </div>
            
            <div class="signature-row" style="justify-content: space-around; margin-top: 15px;">
                <div class="signature-box" style="width: 45%;">
                    <p>Mengetahui,</p>
                    <p><strong>Wali Kelas</strong></p>
                    <div class="space"></div>
                    <div class="name">(..................................................)</div>
                    <div class="role">NIP. .....................................</div>
                </div>
                <div class="signature-box" style="width: 45%;">
                    <p>Menyetujui,</p>
                    <p><strong>Kepala Sekolah</strong></p>
                    <div class="space"></div>
                    <div class="name">(..................................................)</div>
                    <div class="role">NIP. .....................................</div>
                </div>
            </div>
        @else
            {{-- Warning Sedang (SP 1): Hanya Guru BK & Orang Tua (TIDAK ADA SISWA) --}}
            <div class="signature-row" style="justify-content: space-around;">
                <div class="signature-box" style="width: 40%;">
                    <p>Mengetahui,</p>
                    <p><strong>Orang Tua / Wali Siswa</strong></p>
                    <div class="space"></div>
                    <div class="name">(..................................................)</div>
                </div>
                <div class="signature-box" style="width: 40%;">
                    <p>Mengetahui,</p>
                    <p><strong>Guru Bimbingan Konseling</strong></p>
                    <div class="space"></div>
                    <div class="name">{{ auth()->user()->name ?? 'Nama Guru BK' }}</div>
                    <div class="role">NIP. {{ auth()->user()->nip ?? '.....................................' }}</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Script to trigger print on load -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
