<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassData;
use App\Models\Parents;
use App\Models\Student;
use App\Models\DataPointCategory;
use App\Models\PointData;
use App\Models\CaseStudy;
use App\Models\Achievement;
use App\Models\CounselingSession;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Guru BK)
        $guru1 = User::create([
            'name' => 'Rio S.Pd (Guru BK)',
            'email' => 'rdxrio45@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
        ]);

        $guru2 = User::create([
            'name' => 'Siti Rahma S.Psi',
            'email' => 'sitirahma@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
        ]);

        // 2. Seed Kelas SMK (3 Angkatan: Kelas 10, 11, 12 dengan masing-masing 3 rombel per jurusan)
        $jurusans = [
            'RPL' => [
                'name' => 'Rekayasa Perangkat Lunak',
                'count' => 3
            ],
            'MP' => [
                'name' => 'Manajemen Perkantoran',
                'count' => 3
            ],
            'AK' => [
                'name' => 'Akuntansi',
                'count' => 3
            ],
            'BD' => [
                'name' => 'Bisnis Digital',
                'count' => 3
            ],
            'DKV' => [
                'name' => 'Desain Komunikasi Visual',
                'count' => 2
            ],
            'KKBT' => [
                'name' => 'Kriya Kreatif Batik dan Tekstil',
                'count' => 2
            ],
        ];

        $romanGrade = [
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];

        $createdClasses = [];

        foreach (['10', '11', '12'] as $grade) {
            $roman = $romanGrade[$grade];
            foreach ($jurusans as $code => $info) {
                for ($i = 1; $i <= $info['count']; $i++) {
                    $className = "{$roman} {$code} {$i}";
                    $c = ClassData::create([
                        'grade' => $grade,
                        'school_class_name' => $className,
                        'school_class_major' => $info['name'],
                    ]);
                    $createdClasses[$className] = $c;
                }
            }
        }

        // 3. Seed Orang Tua
        $ortu1 = Parents::create([
            'parent_full_name' => 'Bambang Sudrajat',
            'relationship' => 'ayah',
            'address' => 'Jl. Merdeka No. 12, Jakarta',
            'job' => 'Wiraswasta',
            'phone_number' => '081234567890',
            'email' => 'bambang@gmail.com',
        ]);

        $ortu2 = Parents::create([
            'parent_full_name' => 'Dewi Lestari',
            'relationship' => 'ibu',
            'address' => 'Jl. Mawar No. 45, Jakarta',
            'job' => 'PNS',
            'phone_number' => '089876543210',
            'email' => 'dewilestari@gmail.com',
        ]);

        $ortu3 = Parents::create([
            'parent_full_name' => 'Hendra Wijaya',
            'relationship' => 'ayah',
            'address' => 'Jl. Pemuda No. 88, Jakarta',
            'job' => 'Karyawan Swasta',
            'phone_number' => '085711223344',
            'email' => 'hendra@gmail.com',
        ]);

        // 4. Seed User Siswa & Student (Beberapa sampel siswa di angkatan 10, 11, 12)
        $userSiswa1 = User::create([
            'name' => 'Ahmad Rizky',
            'email' => 'siswa@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        $siswa1 = Student::create([
            'user_id' => $userSiswa1->id,
            'full_name' => 'Ahmad Rizky',
            'nis' => '2024001',
            'class_id' => $createdClasses['X RPL 1']->id,
            'parent_id' => $ortu1->id,
            'gender' => 'L',
            'date_of_birth' => '2008-05-14',
            'address' => 'Jl. Merdeka No. 12, Jakarta',
            'phone_number' => '081122334455',
            'total_points' => 15,
            'status' => 'aman',
        ]);

        $userSiswa2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        $siswa2 = Student::create([
            'user_id' => $userSiswa2->id,
            'full_name' => 'Budi Santoso',
            'nis' => '2024002',
            'class_id' => $createdClasses['XI MP 2']->id,
            'parent_id' => $ortu2->id,
            'gender' => 'L',
            'date_of_birth' => '2007-08-20',
            'address' => 'Jl. Mawar No. 45, Jakarta',
            'phone_number' => '082233445566',
            'total_points' => 40,
            'status' => 'peringatan',
        ]);

        $userSiswa3 = User::create([
            'name' => 'Citra Kirana',
            'email' => 'citra@school.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        $siswa3 = Student::create([
            'user_id' => $userSiswa3->id,
            'full_name' => 'Citra Kirana',
            'nis' => '2024003',
            'class_id' => $createdClasses['XII RPL 3']->id,
            'parent_id' => $ortu3->id,
            'gender' => 'P',
            'date_of_birth' => '2006-03-10',
            'address' => 'Jl. Pemuda No. 88, Jakarta',
            'phone_number' => '083344556677',
            'total_points' => 0,
            'status' => 'aman',
        ]);

        // 5. Seed Kategori Poin
        DataPointCategory::create([
            'category_of_violation' => 'Pelanggaran Ringan',
            'category_score_min' => 1,
            'category_score_max' => 25,
            'follow_up' => 'Peringatan Lisan oleh Wali Kelas / Guru BK',
        ]);

        DataPointCategory::create([
            'category_of_violation' => 'Pelanggaran Sedang',
            'category_score_min' => 26,
            'category_score_max' => 50,
            'follow_up' => 'Surat Peringatan 1 & Bimbingan Konseling Intensif',
        ]);

        DataPointCategory::create([
            'category_of_violation' => 'Pelanggaran Berat',
            'category_score_min' => 51,
            'category_score_max' => 100,
            'follow_up' => 'Pemanggilan Orang Tua & Skorsing / Pengembalian ke Orang Tua',
        ]);

        // 6. Seed Pelanggaran Poin
        PointData::create([
            'student_id' => $siswa1->id,
            'violation' => 'Datang terlambat 15 menit',
            'point_number' => 5,
            'violation_date' => now()->subDays(3),
            'description' => 'Terlambat karena macet',
            'recorded_by' => $guru1->id,
        ]);

        PointData::create([
            'student_id' => $siswa1->id,
            'violation' => 'Tidak memakai seragam sesuai jadwal',
            'point_number' => 10,
            'violation_date' => now()->subDay(),
            'description' => 'Tidak membawa atribut pramuka',
            'recorded_by' => $guru1->id,
        ]);

        PointData::create([
            'student_id' => $siswa2->id,
            'violation' => 'Membawa HP saat Jam Pelajaran tanpa izin',
            'point_number' => 20,
            'violation_date' => now()->subDays(5),
            'description' => 'Main game saat pelajaran matematika',
            'recorded_by' => $guru2->id,
        ]);

        PointData::create([
            'student_id' => $siswa2->id,
            'violation' => 'Bolos sekolah jam ke 5-8',
            'point_number' => 20,
            'violation_date' => now()->subDays(2),
            'description' => 'Ditemukan di kantin luar sekolah',
            'recorded_by' => $guru2->id,
        ]);

        $siswa1->recalculateStatus();
        $siswa2->recalculateStatus();

        // 7. Seed Studi Kasus
        CaseStudy::create([
            'student_id' => $siswa2->id,
            'case_title' => 'Indikasi Penurunan Motivasi Belajar dan Masalah Kedisiplinan',
            'case_description' => 'Siswa sering bolos dan terlambat. Perlu pembinaan intensif bersama orang tua.',
            'case_type' => 'belajar',
            'action_taken' => 'Pemanggilan siswa dan diskusi awal mengenai faktor keluarga/lingkungan.',
            'recommendation' => 'Penjadwalan konseling berkala seminggu sekali dan koordinasi wali kelas.',
            'status' => 'proses',
            'handled_by' => $guru1->id,
            'case_date' => now()->subDays(2),
        ]);

        // 8. Seed Prestasi
        Achievement::create([
            'student_id' => $siswa1->id,
            'achievement_name' => 'Juara 1 Lomba Web Design Tingkat Provinsi',
            'achievement_date' => '2024-05-10',
            'achievement_level' => 'Provinsi',
            'achievement_category' => 'Akademik',
            'achievement_status' => 'Terverifikasi',
            'description' => 'Mewakili sekolah di ajang LKS Web Technologies.',
            'recorded_by' => $guru1->id,
        ]);

        Achievement::create([
            'student_id' => $siswa3->id,
            'achievement_name' => 'Juara 2 Hackathon SMK Nasional',
            'achievement_date' => '2024-06-15',
            'achievement_level' => 'Nasional',
            'achievement_category' => 'Akademik',
            'achievement_status' => 'Terverifikasi',
            'description' => 'Menciptakan aplikasi sistem deteksi kecurangan.',
            'recorded_by' => $guru1->id,
        ]);

        // 9. Seed Pengajuan Konseling
        CounselingSession::create([
            'student_id' => $siswa1->id,
            'guru_bk_id' => $guru1->id,
            'requested_date' => now()->addDays(2)->format('Y-m-d'),
            'requested_time' => '10:00:00',
            'topic' => 'Konsultasi Perencanaan Karir & Kuliah RPL',
            'description' => 'Ingin diskusi persiapan masuk perguruan tinggi negeri jurusan Teknik Informatika.',
            'type' => 'individu',
            'status' => 'disetujui',
            'notes' => 'Silakan datang ke ruang BK pada jam istirahat pertama.',
            'approved_at' => now(),
        ]);

        CounselingSession::create([
            'student_id' => $siswa2->id,
            'guru_bk_id' => null,
            'requested_date' => now()->addDays(3)->format('Y-m-d'),
            'requested_time' => '13:00:00',
            'topic' => 'Konsultasi Masalah Belajar & Manajemen Waktu',
            'description' => 'Kesulitan membagi waktu belajar dan bermain.',
            'type' => 'individu',
            'status' => 'menunggu',
        ]);
    }
}
