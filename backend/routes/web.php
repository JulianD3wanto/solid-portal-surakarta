<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Citizen\ApplicationController;
use App\Http\Controllers\Citizen\NotificationController as CitizenNotificationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'home'])->name('home');
Route::get('/layanan', [PortalController::class, 'services'])->name('services.index');
Route::get('/layanan/{documentType:slug}', [PortalController::class, 'service'])->name('services.show');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::view('/profil', 'pages.profile')->name('profile');
Route::view('/visi-misi', 'pages.vision')->name('vision');
Route::view('/ppid', 'pages.ppid')->name('ppid');
Route::view('/kontak', 'pages.contact')->name('contact');
Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:auth')->name('login.store');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:auth')->name('register.store');

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profil-saya', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-saya', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $applications = \App\Models\Application::query()
            ->with('documentType')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('citizen.dashboard', [
            'applications' => $applications,
            'activeCount' => $applications->whereNotIn('status', ['completed', 'rejected'])->count(),
            'revisionCount' => $applications->whereIn('status', ['needs_revision', 'rejected'])->count(),
            'completedCount' => $applications->where('status', 'completed')->count(),
        ]);
    })->name('dashboard');
    Route::get('/permohonan/{application}/berkas/{applicationFile}/view', [ApplicationController::class, 'viewFile'])->name('citizen.applications.file-view');
    Route::post('/permohonan/{application}/berkas/{applicationFile}/hapus', [ApplicationController::class, 'deleteFile'])->name('citizen.applications.file-delete');

    Route::delete('/permohonan/{application}', function (\App\Models\Application $application) {
        return redirect()->route('citizen.applications.edit', $application)->withErrors(['file' => 'Silakan gunakan tombol Hapus pada baris berkas yang dipilih.']);
    });
    Route::get('/permohonan/{application}/edit', [ApplicationController::class, 'edit'])->name('citizen.applications.edit');
    Route::put('/permohonan/{application}', [ApplicationController::class, 'update'])->name('citizen.applications.update');
    Route::get('/permohonan/{documentType:slug}/buat', [ApplicationController::class, 'create'])->name('citizen.applications.create');
    Route::post('/permohonan/{documentType:slug}', [ApplicationController::class, 'store'])->name('citizen.applications.store');
    Route::get('/notifikasi', [CitizenNotificationController::class, 'index'])->name('citizen.notifications.index');
    Route::post('/notifikasi/{notification}/read', [CitizenNotificationController::class, 'markRead'])->name('citizen.notifications.read');
    Route::post('/notifikasi/read-all', [CitizenNotificationController::class, 'markAllRead'])->name('citizen.notifications.read-all');
});

Route::middleware(['auth', 'role:admin,kelurahan_officer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/permohonan', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/permohonan/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/permohonan/{application}/approve', [AdminApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/permohonan/{application}/reject', [AdminApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/permohonan/{application}/result', [AdminApplicationController::class, 'uploadResult'])->name('applications.result');
    Route::post('/berkas/{applicationFile}/verify', [AdminApplicationController::class, 'verifyFile'])->name('files.verify');
    Route::get('/berkas/{applicationFile}/view', [AdminApplicationController::class, 'viewFile'])->name('files.view');
    Route::get('/berkas/{applicationFile}/download', [AdminApplicationController::class, 'downloadFile'])->name('files.download');

    Route::get('/berita', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/berita/buat', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/berita', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/berita/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/berita/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/berita/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::get('/notifikasi', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/count', [AdminNotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::post('/notifikasi/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

Route::middleware('auth')->get('/permohonan/{application}/view-result', [AdminApplicationController::class, 'viewResult'])->name('citizen.applications.view-result');
Route::middleware('auth')->get('/permohonan/{application}/download-result', [AdminApplicationController::class, 'downloadResult'])->name('citizen.applications.download-result');
