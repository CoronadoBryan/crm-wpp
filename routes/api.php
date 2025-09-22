<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Route::get('/test', function () {
//     return 'API funcionando';
// });

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [WebhookController::class, 'verify'])->name('verify');
        Route::post('/', [WebhookController::class, 'receive'])->name('receive');
    });
    
    // Telegram (ejemplo futuro)
    // Route::prefix('telegram')->name('telegram.')->group(function () {
    //     Route::post('/', [TelegramController::class, 'receive'])->name('receive');
    // });
});