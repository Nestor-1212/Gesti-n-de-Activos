<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Autenticación ──────────────────────────────────────────────────────────

Route::get('/', [LoginController::class, 'showLogin'])->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post')
    ->middleware('throttle:login');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Área protegida ─────────────────────────────────────────────────────────

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Equipos / Activos ──────────────────────────────────────────────────
    Route::resource('equipment', EquipmentController::class);
    Route::get('equipment/{equipment}/qr', [EquipmentController::class, 'generateQr'])
        ->name('equipment.qr');

    // ── Colaboradores ──────────────────────────────────────────────────────
    Route::resource('collaborators', CollaboratorController::class);

    // ── Departamentos ──────────────────────────────────────────────────────
    Route::resource('departments', DepartmentController::class);

    // ── Asignaciones ───────────────────────────────────────────────────────
    Route::resource('assignments', AssignmentController::class);

    Route::post('assignments/{assignment}/return', [AssignmentController::class, 'returnEquipment'])
        ->name('assignments.return');

    Route::get('assignments/{assignment}/acta', [AssignmentController::class, 'printActa'])
        ->name('assignments.acta');

    // ── Mantenimiento ──────────────────────────────────────────────────────
    Route::resource('maintenance', MaintenanceController::class);

    // ── Escáner (rate-limited) ─────────────────────────────────────────────
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');

    Route::post('/scanner/lookup', [ScannerController::class, 'lookup'])
        ->name('scanner.lookup')
        ->middleware('throttle:scanner');

    // ── Reportes ───────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                   [ReportController::class, 'index'])->name('index');
        Route::get('/inventory',          [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/by-collaborator',    [ReportController::class, 'byCollaborator'])->name('by-collaborator');
        Route::get('/by-department',      [ReportController::class, 'byDepartment'])->name('by-department');
        Route::get('/warranty-expiring',  [ReportController::class, 'warrantyExpiring'])->name('warranty-expiring');
        Route::get('/assignment-history', [ReportController::class, 'assignmentHistory'])->name('assignment-history');
    });

    // ── Notificaciones ─────────────────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',                              [NotificationController::class, 'index'])->name('index');
        Route::post('{notification}/read',           [NotificationController::class, 'markRead'])->name('read');
        Route::post('read-all',                      [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('{notification}',              [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ── Bitácora ───────────────────────────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // ── Usuarios (solo admin) ──────────────────────────────────────────────
    Route::middleware('role:super-admin,admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
