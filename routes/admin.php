<?php

use App\Http\Controllers\Admin\AiAssistantController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin — prefix /admin, names admin.*, middleware: web, auth, verified, admin
|--------------------------------------------------------------------------
*/

Route::get('/2fa', [TwoFactorChallengeController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorChallengeController::class, 'store'])
    ->middleware('throttle:admin_2fa')
    ->name('2fa.store');
Route::post('/2fa/resend', [TwoFactorChallengeController::class, 'resend'])
    ->middleware('throttle:admin_2fa_resend')
    ->name('2fa.resend');

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('posts', PostController::class);
Route::resource('projects', ProjectController::class);
Route::resource('services', ServiceController::class);

Route::resource('leads', LeadController::class)->only(['index', 'show', 'destroy']);
Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');

Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);

Route::post('/media/upload', [MediaController::class, 'upload'])
    ->middleware('throttle:10,1')
    ->name('media.upload');

Route::post('/api/ai/generate', [AiAssistantController::class, 'generate'])
    ->middleware('throttle:5,1')
    ->name('ai.generate');
