<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketCommentController;
use App\Http\Controllers\Admin\TicketAttachmentController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\GlobalSearchController;

/* --------------------------------------------------------------------------
 | Public routes
 |-------------------------------------------------------------------------- */
Route::get('/', function () {
    return view('welcome');
});

// Dashboard – requires authenticated and verified users
Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:super-admin'])
    ->name('dashboard');

/* --------------------------------------------------------------------------
 | Admin panel – protected by authentication, verification, admin role
 |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Main admin dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ------------------------------------------------------------------
        // Organizations
        // ------------------------------------------------------------------
        Route::resource('organizations', OrganizationController::class)
            ->middleware('permission:manage organizations');

        // ------------------------------------------------------------------
        // Users
        // ------------------------------------------------------------------
        Route::resource('users', UserController::class)
            ->middleware('permission:manage users');

        // ------------------------------------------------------------------
        // Roles & Permissions (Spatie)
        // ------------------------------------------------------------------
        Route::resource('roles', RoleController::class)
            ->middleware('permission:manage roles');
        Route::resource('permissions', PermissionController::class)
            ->middleware('permission:manage permissions');

        // ------------------------------------------------------------------
        // Tickets
        // ------------------------------------------------------------------
        Route::resource('tickets', TicketController::class)
            ->middleware('permission:manage tickets');

        // Ticket comments – shallow nested routes (no show)
        Route::resource('tickets.comments', TicketCommentController::class)
            ->shallow()
            ->except(['show'])
            ->middleware('permission:manage ticket comments');

        // Ticket attachments – shallow nested routes (no show)
        Route::resource('tickets.attachments', TicketAttachmentController::class)
            ->shallow()
            ->except(['show'])
            ->middleware('permission:manage ticket attachments');

        // ------------------------------------------------------------------
        // Audit Logs
        // ------------------------------------------------------------------
        Route::resource('audit-logs', AuditLogController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view audit logs');

        // ------------------------------------------------------------------
        // Global Search
        // ------------------------------------------------------------------
        Route::get('search', GlobalSearchController::class)
            ->name('search')
            ->middleware('permission:search');

        // ------------------------------------------------------------------
        // Settings placeholder
        // ------------------------------------------------------------------
        Route::view('settings', 'admin.settings')->name('settings')
            ->middleware('permission:manage settings');
    });

/* --------------------------------------------------------------------------
 | Profile management – accessible to any authenticated user
 |-------------------------------------------------------------------------- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* --------------------------------------------------------------------------
 | Authentication routes (generated by Laravel Breeze / Jetstream)
 |-------------------------------------------------------------------------- */
require __DIR__.'/auth.php';
?>
