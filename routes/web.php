<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Redirigir la raíz directamente a Filament
Route::get('/', function () {
    return redirect('/admin');
});



// Comentar rutas que ya no necesitas con Filament
/*
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});
*/

// Comentar las rutas de autenticación ya que usarás Filament
// require __DIR__.'/auth.php';
