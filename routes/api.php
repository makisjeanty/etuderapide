<?php

use App\Http\Controllers\Api\Admin\DashboardSummaryController;
use App\Http\Controllers\Api\Admin\LeadDestroyController;
use App\Http\Controllers\Api\Admin\LeadIndexController;
use App\Http\Controllers\Api\Admin\LeadShowController;
use App\Http\Controllers\Api\Admin\LeadUpdateController;
use App\Http\Controllers\Api\Admin\PostDestroyController;
use App\Http\Controllers\Api\Admin\PostIndexController;
use App\Http\Controllers\Api\Admin\PostShowController;
use App\Http\Controllers\Api\Admin\PostStoreController;
use App\Http\Controllers\Api\Admin\PostUpdateController;
use App\Http\Controllers\Api\Admin\ProjectDestroyController;
use App\Http\Controllers\Api\Admin\ProjectIndexController;
use App\Http\Controllers\Api\Admin\ProjectShowController;
use App\Http\Controllers\Api\Admin\ProjectStoreController;
use App\Http\Controllers\Api\Admin\ProjectUpdateController;
use App\Http\Controllers\Api\Admin\ServiceDestroyController;
use App\Http\Controllers\Api\Admin\ServiceIndexController;
use App\Http\Controllers\Api\Admin\ServiceShowController;
use App\Http\Controllers\Api\Admin\ServiceStoreController;
use App\Http\Controllers\Api\Admin\ServiceUpdateController;
use App\Http\Controllers\Api\Auth\TokenController;
use App\Http\Controllers\Api\CurrentUserController;
use App\Http\Controllers\Api\Public\PostIndexController as PublicPostIndexController;
use App\Http\Controllers\Api\Public\PostShowController as PublicPostShowController;
use App\Http\Controllers\Api\Public\ProjectIndexController as PublicProjectIndexController;
use App\Http\Controllers\Api\Public\ProjectShowController as PublicProjectShowController;
use App\Http\Controllers\Api\Public\ServiceIndexController as PublicServiceIndexController;
use App\Http\Controllers\Api\Public\ServiceShowController as PublicServiceShowController;
use App\Http\Controllers\Api\TokenManagementController;
use Illuminate\Support\Facades\Route;

$registerApiRoutes = function (string $namePrefix = 'api.', ?string $versionPrefix = null): void {
    $router = Route::name($namePrefix);

    if ($versionPrefix !== null) {
        $router = $router->prefix($versionPrefix);
    }

    $router->group(function (): void {
        Route::post('/login', [TokenController::class, 'store'])
            ->middleware('throttle:api_login')
            ->name('login');

        Route::prefix('public')->name('public.')->group(function (): void {
            Route::get('/posts', PublicPostIndexController::class)
                ->middleware('throttle:api_public')
                ->name('posts.index');
            Route::get('/posts/{slug}', PublicPostShowController::class)
                ->middleware('throttle:api_public')
                ->name('posts.show');
            Route::get('/projects', PublicProjectIndexController::class)
                ->middleware('throttle:api_public')
                ->name('projects.index');
            Route::get('/projects/{slug}', PublicProjectShowController::class)
                ->middleware('throttle:api_public')
                ->name('projects.show');
            Route::get('/services', PublicServiceIndexController::class)
                ->middleware('throttle:api_public')
                ->name('services.index');
            Route::get('/services/{slug}', PublicServiceShowController::class)
                ->middleware('throttle:api_public')
                ->name('services.show');
        });

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('/me', CurrentUserController::class)->name('me');
            Route::post('/logout', [TokenController::class, 'destroy'])->name('logout');
            Route::post('/tokens', [TokenManagementController::class, 'store'])->name('tokens.store');
            Route::get('/tokens', [TokenManagementController::class, 'index'])->name('tokens.index');
            Route::delete('/tokens/{token}', [TokenManagementController::class, 'destroy'])->name('tokens.destroy');

            Route::prefix('admin')->name('admin.')->group(function (): void {
                Route::get('/summary', DashboardSummaryController::class)->name('summary');

                Route::get('/leads', LeadIndexController::class)->name('leads.index');
                Route::get('/leads/{lead}', LeadShowController::class)->name('leads.show');
                Route::patch('/leads/{lead}', LeadUpdateController::class)->name('leads.update');
                Route::delete('/leads/{lead}', LeadDestroyController::class)->name('leads.destroy');

                Route::get('/posts', PostIndexController::class)->name('posts.index');
                Route::post('/posts', PostStoreController::class)->name('posts.store');
                Route::get('/posts/{post}', PostShowController::class)->name('posts.show');
                Route::match(['put', 'patch'], '/posts/{post}', PostUpdateController::class)->name('posts.update');
                Route::delete('/posts/{post}', PostDestroyController::class)->name('posts.destroy');

                Route::get('/projects', ProjectIndexController::class)->name('projects.index');
                Route::post('/projects', ProjectStoreController::class)->name('projects.store');
                Route::get('/projects/{project}', ProjectShowController::class)->name('projects.show');
                Route::match(['put', 'patch'], '/projects/{project}', ProjectUpdateController::class)->name('projects.update');
                Route::delete('/projects/{project}', ProjectDestroyController::class)->name('projects.destroy');

                Route::get('/services', ServiceIndexController::class)->name('services.index');
                Route::post('/services', ServiceStoreController::class)->name('services.store');
                Route::get('/services/{service}', ServiceShowController::class)->name('services.show');
                Route::match(['put', 'patch'], '/services/{service}', ServiceUpdateController::class)->name('services.update');
                Route::delete('/services/{service}', ServiceDestroyController::class)->name('services.destroy');
            });
        });
    });
};

// Legacy unversioned routes kept for backward compatibility.
$registerApiRoutes('api.');

// Versioned routes for future API evolution.
$registerApiRoutes('api.v1.', 'v1');
