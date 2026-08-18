<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan Konseling</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 0;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }
        .signature-box .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekapan Konseling Siswa</h2>
        <p>Bulan: {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Siswa</th>
                <th width="15%">Kelas</th>
                <th width="25%">Topik & Jenis</th>
                <th width="20%">Status / Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $c)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $c->requested_date ? \Carbon\Carbon::parse($c->requested_date)->format('d-m-Y') : '-' }}<br>{{ $c->requested_time ? \Carbon\Carbon::parse($c->requested_time)->format('H:i') : '-' }}</td>
                    <td>
                        {{ $c->student?->full_name ?? 'Siswa Terhapus' }}<br>
                        @if($c->type === 'kelompok' && $c->additionalStudents()->isNotEmpty())
                            <small style="display: block; font-size: 10px; color: #555; margin-top: 2px;">
                                Anggota: {{ $c->additionalStudents()->pluck('full_name')->join(', ') }}
                            </small>
                        @endif
                        <small>NIS: {{ $c->student?->nis }}</small>
                    </td>
                    <td class="text-center">{{ $c->student?->class?->school_class_name ?? '-' }}</td>
                    <td>
                        <strong>{{ $c->topic }}</strong> ({{ ucfirst($c->type) }})<br>
                        <small>{{ $c->description }}</small>
                    </td>
                    <td>
                        Status: <strong>{{ ucfirst($c->status) }}</strong><br>
                        @if($c->notes)
                            <small>Catatan: {{ $c->notes }}</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data konseling pada bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p><strong>Guru Bimbingan Konseling</strong></p>
            <div class="name" style="text-decoration: none;">{{ auth()->user()->name ?? 'Nama Guru BK' }}</div>
            <div style="font-size: 11px; margin-top: 4px;">NIP. {{ auth()->user()->nip ?? '.....................................' }}</div>
        </div>
    </div>
</body>
</html>
