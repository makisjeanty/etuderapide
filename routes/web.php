<?php

use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\Web\AuditReportController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ProjectPublicController;
use App\Http\Controllers\Web\ServicePublicController;
use App\Http\Controllers\Web\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/audit-report/{lead}', [AuditReportController::class, 'download'])
    ->middleware(['signed', 'throttle:5,1'])
    ->name('audit.report.download');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sobre', [PageController::class, 'about'])->name('about');
Route::get('/contato', [PageController::class, 'contact'])->name('contact');
Route::post('/contato', [PageController::class, 'submitContact'])->name('contact.submit')->middleware('bot_protection');
Route::get('/contato/sucesso', [PageController::class, 'contactSuccess'])->name('contact.success');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('blog.show');

Route::post('/analyze', [AiAnalysisController::class, 'analyze'])
    ->name('ai.analyze')
    ->middleware(['bot_protection', 'throttle:ai_analysis']);

Route::get('/projects', [ProjectPublicController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectPublicController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('projects.show');

Route::get('/services', [ServicePublicController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServicePublicController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('services.show');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
