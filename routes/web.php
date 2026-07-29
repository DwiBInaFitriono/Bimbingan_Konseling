<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CounselingSessionController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Achievement;
use App\Http\Controllers\CaseReport;
use App\Http\Controllers\ClassData;
use App\Http\Controllers\DataClass;
use App\Http\Controllers\PointCategory;
use App\Http\Controllers\ParentStudent;
use App\Http\Controllers\DataPoint;
use App\Http\Controllers\Student;
use App\Http\Controllers\Achievements;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::any('/debug', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'session_id' => $request->session()->getId(),
        'session_token' => $request->session()->token(),
        'input_token' => $request->input('_token'),
        'header_token' => $request->header('X-CSRF-TOKEN'),
        'cookie' => $request->cookie('sistem_bk_session'),
        'all_inputs' => $request->all(),
    ]);
});

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isSiswa()
            ? redirect()->route('counseling.siswa')
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/help', 'pages.help')->name('help.center');
    Route::view('/settings', 'pages.settings')->name('settings.index');
    Route::get('/profile', function () {
        return view('pages.profile', ['user' => Auth::user()]);
    })->name('profile.show');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Route untuk Siswa & Guru BK
    Route::middleware(CheckRole::class . ':siswa,guru_bk')->group(function () {
        Route::get('/konseling-siswa', [CounselingSessionController::class, 'studentIndex'])->name('counseling.siswa');
        Route::post('/konseling-siswa/simpan', [CounselingSessionController::class, 'store'])->name('counseling.store');
        Route::post('/konseling-siswa/batalkan/{id}', [CounselingSessionController::class, 'cancel'])->name('counseling.cancel');
        Route::get('/siswa/cetak-peringatan/{id}', [Admin::class, 'printWarningLetter'])->name('siswa.cetak.peringatan');
    });

    // Route khusus Guru BK / Admin
    Route::middleware(CheckRole::class . ':guru_bk')->group(function () {
        Route::get('template', [Admin::class, 'index'])->name('dashboard');

        // Counseling Management & Laporan Rekapan Guru BK
        Route::get('konseling', [CounselingSessionController::class, 'index'])->name('counseling.index');
        Route::get('konseling/laporan', [CounselingSessionController::class, 'report'])->name('counseling.report');
        Route::get('konseling/laporan/cetak', [CounselingSessionController::class, 'exportPdf'])->name('counseling.report.pdf');
        Route::post('konseling/setujui/{id}', [CounselingSessionController::class, 'approve'])->name('counseling.approve');
        Route::post('konseling/tolak/{id}', [CounselingSessionController::class, 'reject'])->name('counseling.reject');
        Route::post('konseling/selesai/{id}', [CounselingSessionController::class, 'complete'])->name('counseling.complete');
        Route::get('konseling/hapus/{id}', [CounselingSessionController::class, 'destroy'])->name('counseling.destroy');

        // Routes Siswa
        Route::get('siswa', [Admin::class, 'Siswa'])->name('siswa.tampil');
        Route::get('/tambah', [Admin::class, 'tambahSiswa']);
        Route::post('simpan', [Admin::class, 'simpanSiswa']);
        Route::get('/hapus/{id}', [Admin::class, 'hapusSiswa']);
        Route::get('/edit/{id}', [Admin::class, 'editSiswa'])->name('siswa.edit');
        Route::post('/update/{id}', [Admin::class, 'updateSiswa']);

        // Routes Class
        Route::get('kelas', [DataClass::class, 'classData'])->name('kelas.tampil');
        Route::get('tambahkelas', [DataClass::class, 'addClass']);
        Route::post('simpankelas', [DataClass::class, 'storeClass']);
        Route::get('hapuskelas/{id}', [DataClass::class, 'destroyClass']);
        Route::get('/editkelas/{id}', [DataClass::class, 'editClass']);
        Route::post('/updatekelas/{id}', [DataClass::class, 'updateClass']);

        // Route Parents
        Route::get('ortu', [ParentStudent::class, 'parentData'])->name('ortu.tampil');
        Route::get('tambahparent', [ParentStudent::class, 'addParent']);
        Route::post('simpanparent', [ParentStudent::class, 'storeParent']);
        Route::get('hapusparent/{id}', [ParentStudent::class, 'destroyParent']);
        Route::get('/editparent/{id}', [ParentStudent::class, 'editParent']);
        Route::post('/updateparent/{id}', [ParentStudent::class, 'updateParent']);

        // Route Points
        Route::get('point', [DataPoint::class, 'dataPoint'])->name('point.tampil');
        Route::get('tambahpoint', [DataPoint::class, 'createPoint']);
        Route::post('simpanpoint', [DataPoint::class, 'storePoint']);
        Route::get('hapuspoint/{id}', [DataPoint::class, 'destroyPoint']);
        Route::get('/editpoint/{id}', [DataPoint::class, 'editPoint']);
        Route::post('/updatepoint/{id}', [DataPoint::class, 'updatePoint']);

        // Route Points Category
        Route::get('kategori', [PointCategory::class, 'indexPointCategory'])->name('kategori.tampil');
        Route::get('tambahkategori', [PointCategory::class, 'createPointCategory']);
        Route::post('simpankategori', [PointCategory::class, 'storePointCategory']);
        Route::get('hapuskategori/{id}', [PointCategory::class, 'destroyPointCategory']);
        Route::get('/editkategori/{id}', [PointCategory::class, 'editPointCategory']);
        Route::post('/updatekategori/{id}', [PointCategory::class, 'updatePointCategory']);

        // Route Case Report
        Route::get('studykasus', [CaseReport::class, 'indexCaseReport'])->name('studykasus.tampil');
        Route::get('tambahstudykasus', [CaseReport::class, 'createCaseReport']);
        Route::post('simpanstudykasus', [CaseReport::class, 'storeCaseReport']);
        Route::get('hapusstudykasus/{id}', [CaseReport::class, 'destroyCaseReport']);
        Route::get('/editstudykasus/{id}', [CaseReport::class, 'editCaseReport']);
        Route::post('/updatestudykasus/{id}', [CaseReport::class, 'updateCaseReport']);
        Route::post('studykasus/selesaikan/{id}', [CaseReport::class, 'completeCase'])->name('studykasus.complete');
        Route::post('studykasus/sanksi-poin/{id}', [CaseReport::class, 'applyPointSanction'])->name('studykasus.sanction');
        Route::get('studykasus/cetak/{id}', [CaseReport::class, 'printCasePdf'])->name('studykasus.pdf');

        // Route Achievement
        Route::get('dataprestasi', [Achievements::class, 'indexAchievement'])->name('dataprestasi.tampil');
        Route::get('tambahprestasi', [Achievements::class, 'createAchievement']);
        Route::post('simpanprestasi', [Achievements::class, 'storeAchievement']);
        Route::get('hapusprestasi/{id}', [Achievements::class, 'destroyAchievement']);
        Route::get('/editprestasi/{id}', [Achievements::class, 'editAchievement']);
        Route::post('/updateprestasi/{id}', [Achievements::class, 'updateAchievement']);
    });
});
