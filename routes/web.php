<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru_BK\Admin;
use App\Http\Controllers\Guru_BK\Achievements;
use App\Http\Controllers\Guru_BK\CaseReport;
use App\Http\Controllers\Guru_BK\DataClass;
use App\Http\Controllers\Guru_BK\PointCategory;
use App\Http\Controllers\Guru_BK\ParentStudent;
use App\Http\Controllers\Guru_BK\DataPoint;
use App\Http\Controllers\Guru_BK\CounselingSessionController;

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login.gurubk');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function() { return redirect()->route('login.gurubk'); })->name('login');
    Route::get('/login/gurubk', [AuthController::class, 'showLoginGuru'])->name('login.gurubk');
    Route::post('/login/perform', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.perform');
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

    Route::middleware(CheckRole::class . ':guru_bk')->group(function () {
        Route::get('template', [Admin::class, 'index'])->name('dashboard');

        Route::controller(CounselingSessionController::class)->group(function () {
            Route::get('konseling', 'index')->name('counseling.index');
            Route::get('konseling/laporan', 'report')->name('counseling.report');
            Route::get('konseling/laporan/cetak', 'exportPdf')->name('counseling.report.pdf');
            Route::post('konseling/simpan', 'store')->name('counseling.store.gurubk');
            Route::post('konseling/setujui/{id}', 'approve')->name('counseling.approve');
            Route::post('konseling/tolak/{id}', 'reject')->name('counseling.reject');
            Route::post('konseling/selesai/{id}', 'complete')->name('counseling.complete');
            Route::delete('konseling/hapus/{id}', 'destroy')->name('counseling.destroy');
        });

        Route::controller(Admin::class)->group(function () {
            Route::get('siswa', 'Siswa')->name('siswa.tampil');
            Route::get('/tambah', 'tambahSiswa');
            Route::post('simpan', 'simpanSiswa');
            Route::delete('/hapus/{id}', 'hapusSiswa');
            Route::get('/edit/{id}', 'editSiswa')->name('siswa.edit');
            Route::post('/update/{id}', 'updateSiswa');
            Route::get('/siswa/{id}/cetak-sp', 'printWarningLetter')->name('siswa.cetak.peringatan');
        });

        Route::controller(DataClass::class)->group(function () {
            Route::get('kelas', 'classData')->name('kelas.tampil');
            Route::get('tambahkelas', 'addClass');
            Route::post('simpankelas', 'storeClass');
            Route::delete('hapuskelas/{id}', 'destroyClass');
            Route::get('/editkelas/{id}', 'editClass');
            Route::post('/updatekelas/{id}', 'updateClass');
        });

        Route::controller(ParentStudent::class)->group(function () {
            Route::get('ortu', 'parentData')->name('ortu.tampil');
            Route::get('tambahparent', 'addParent');
            Route::post('simpanparent', 'storeParent');
            Route::delete('hapusparent/{id}', 'destroyParent');
            Route::get('/editparent/{id}', 'editParent');
            Route::post('/updateparent/{id}', 'updateParent');
        });

        Route::controller(DataPoint::class)->group(function () {
            Route::get('point', 'dataPoint')->name('point.tampil');
            Route::get('tambahpoint', 'createPoint');
            Route::post('simpanpoint', 'storePoint');
            Route::delete('hapuspoint/{id}', 'destroyPoint');
            Route::get('/editpoint/{id}', 'editPoint');
            Route::post('/updatepoint/{id}', 'updatePoint');
        });

        Route::controller(PointCategory::class)->group(function () {
            Route::get('kategori', 'indexPointCategory')->name('kategori.tampil');
            Route::get('tambahkategori', 'createPointCategory');
            Route::post('simpankategori', 'storePointCategory');
            Route::delete('hapuskategori/{id}', 'destroyPointCategory');
            Route::get('/editkategori/{id}', 'editPointCategory');
            Route::post('/updatekategori/{id}', 'updatePointCategory');
        });

        Route::controller(CaseReport::class)->group(function () {
            Route::get('studykasus', 'indexCaseReport')->name('studykasus.tampil');
            Route::get('tambahstudykasus', 'createCaseReport');
            Route::post('simpanstudykasus', 'storeCaseReport');
            Route::delete('hapusstudykasus/{id}', 'destroyCaseReport');
            Route::get('/editstudykasus/{id}', 'editCaseReport');
            Route::post('/updatestudykasus/{id}', 'updateCaseReport');
            Route::post('studykasus/selesaikan/{id}', 'completeCase')->name('studykasus.complete');
            Route::post('studykasus/sanksi-poin/{id}', 'applyPointSanction')->name('studykasus.sanction');
            Route::get('studykasus/cetak/{id}', 'printCasePdf')->name('studykasus.pdf');
        });

        Route::controller(Achievements::class)->group(function () {
            Route::get('dataprestasi', 'indexAchievement')->name('dataprestasi.tampil');
            Route::get('tambahprestasi', 'createAchievement');
            Route::post('simpanprestasi', 'storeAchievement');
            Route::delete('hapusprestasi/{id}', 'destroyAchievement');
            Route::get('/editprestasi/{id}', 'editAchievement');
            Route::post('/updateprestasi/{id}', 'updateAchievement');
        });
    });
});
