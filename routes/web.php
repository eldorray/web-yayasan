<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Public\Home;
use App\Livewire\Settings\Appearance as SettingsAppearance;
use App\Livewire\Settings\Profile as SettingsProfile;
use App\Livewire\Settings\Theme as SettingsTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute publik (sebagian placeholder — akan diisi tugas berikutnya)
Route::name('public.')->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/tentang', Home::class)->name('about');
    Route::get('/sekolah', Home::class)->name('schools.index');
    Route::get('/berita', Home::class)->name('news.index');
    Route::get('/galeri', Home::class)->name('gallery.index');
    Route::get('/ppdb', Home::class)->name('ppdb');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', '/settings/profile');
        Route::get('/profile', SettingsProfile::class)->name('profile');
        Route::get('/appearance', SettingsAppearance::class)->name('appearance');
        Route::get('/theme', SettingsTheme::class)->name('theme');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});