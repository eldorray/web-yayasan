<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Settings\Appearance as SettingsAppearance;
use App\Livewire\Settings\Profile as SettingsProfile;
use App\Livewire\Settings\Theme as SettingsTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

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