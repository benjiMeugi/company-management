<?php

use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TitleController;
// Vos nouveaux contrôleurs pour le module Paie
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PayslipLineController;
use App\Http\Controllers\PayrollLineTypeController;
use App\Http\Controllers\ClassificationPayrollLineTypeController;
use App\Http\Controllers\AbsenceTypeController;
use App\Http\Controllers\AbsenceRequestController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\LeaveCreditController;
use App\Http\Controllers\LeaveBalanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

dd(0);

function resolveAbility(string $start_ability, string $end_ability)
{
    // TODO: Juste pour le dev, à supprimer plus tard qd l'auth sera en place
    return null;
    return $start_ability. '-'. $end_ability;
}

// TODO: Appliquer l'auth middleware sur toutes les routes
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// --- ROUTES EXISTANTES DE VOTRE GROUPE (NE PAS TOUCHER) ---

Route::prefix('title')->group(function () {
    $controller = TitleController::class;
    $startAbility = 'title';
    Route::get('/{id?}', [$controller, 'index'])->middleware(resolveAbility($startAbility, 'list'));
    Route::post('/', [$controller, 'store'])->middleware(resolveAbility($startAbility, 'create'));
    Route::put('/{id}', [$controller, 'update'])->middleware(resolveAbility($startAbility, 'update'));
    Route::delete('/{id}', [$controller, 'delete'])->middleware(resolveAbility($startAbility, 'delete'));
});

Route::prefix('position')->group(function () {
    $controller = PositionController::class;
    $startAbility = 'position';
    Route::get('/{id?}', [$controller, 'index'])->middleware(resolveAbility($startAbility, 'list'));
    Route::post('/', [$controller, 'store'])->middleware(resolveAbility($startAbility, 'create'));
    Route::put('/{id}', [$controller, 'update'])->middleware(resolveAbility($startAbility, 'update'));
    Route::delete('/{id}', [$controller, 'delete'])->middleware(resolveAbility($startAbility, 'delete'));
});

Route::prefix('classification')->group(function () {
    $controller = ClassificationController::class;
    $startAbility = 'classification';
    Route::get('/{id?}', [$controller, 'index'])->middleware(resolveAbility($startAbility, 'list'));
    Route::post('/', [$controller, 'store'])->middleware(resolveAbility($startAbility, 'create'));
    Route::put('/{id}', [$controller, 'update'])->middleware(resolveAbility($startAbility, 'update'));
    Route::delete('/{id}', [$controller, 'delete'])->middleware(resolveAbility($startAbility, 'delete'));
});

Route::prefix('employe')->group(function () {
    $controller = EmployeController::class;
    $startAbility = 'employe';
    Route::get('/{id?}', [$controller, 'index'])->middleware(resolveAbility($startAbility, 'list'));
    Route::post('/', [$controller, 'store'])->middleware(resolveAbility($startAbility, 'create'));
    Route::put('/{id}', [$controller, 'update'])->middleware(resolveAbility($startAbility, 'update'));
    Route::delete('/{id}', [$controller, 'delete'])->middleware(resolveAbility($startAbility, 'delete'));
});


// --- VOS NOUVELLES ROUTES (MODULE GESTION DE LA PAIE) ---

Route::prefix('payroll')->group(function () {

    // --- CONTRATS ---
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::post('/contract-types', [ContractTypeController::class, 'store']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);

    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::get('/contracts/{id}', [ContractController::class, 'show']);

    // --- BULLETINS DE PAIE & CALCULS ---
    Route::get('/payslips', [PayslipController::class, 'index']);
    Route::get('/payslips/{id}', [PayslipController::class, 'show']);
    Route::post('/payslips/generate', [PayslipController::class, 'generate']); // Route de calcul automatique
    
    Route::get('/payslip-lines/{id}', [PayslipLineController::class, 'show']);

    // --- RUBRIQUES & CONFIGURATIONS ---
    Route::post('/line-types', [PayrollLineTypeController::class, 'store']);
    Route::get('/line-types', [PayrollLineTypeController::class, 'index']);
    Route::post('/classifications/lines', [ClassificationPayrollLineTypeController::class, 'store']);

    // --- ABSENCES & CONGÉS ---
    Route::get('/absence-types', [AbsenceTypeController::class, 'index']);
    Route::post('/absence-types', [AbsenceTypeController::class, 'store']);

    Route::get('/absence-requests', [AbsenceRequestController::class, 'index']);
    Route::post('/absence-requests', [AbsenceRequestController::class, 'store']);

    Route::get('/absences', [AbsenceController::class, 'index']);

    Route::post('/leave-credits', [LeaveCreditController::class, 'store']);
    Route::get('/leave-balances/employee/{employee_id}', [LeaveBalanceController::class, 'show']);
});
