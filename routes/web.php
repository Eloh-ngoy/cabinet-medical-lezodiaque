<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DocumentExportController;
use App\Http\Controllers\HospitalizationController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\MedicalFileController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Password change (forced on first login)
Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change')->middleware('auth');
Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.post')->middleware('auth');

// Home route
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patient management routes
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/medical-file', [MedicalFileController::class, 'show'])->name('patients.medical-file');

    // Consultation routes
    Route::resource('consultations', ConsultationController::class);
    Route::get('/patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('patients.consultations.create');

    // Appointment routes
    Route::resource('appointments', AppointmentController::class)->names([
        'index' => 'appointments.index',
        'create' => 'appointments.create',
        'store' => 'appointments.store',
        'show' => 'appointments.show',
        'edit' => 'appointments.edit',
        'update' => 'appointments.update',
        'destroy' => 'appointments.destroy',
    ]);
    Route::get('/patients/{patient}/appointments/create', [AppointmentController::class, 'create'])->name('patients.appointments.create');

    // User management routes
    Route::resource('users', UserManagementController::class);
    Route::resource('hospitalizations', HospitalizationController::class);
    Route::put('/hospitalizations/{hospitalization}/discharge', [HospitalizationController::class, 'discharge'])->name('hospitalizations.discharge');
    Route::resource('laboratory', LaboratoryController::class);
    Route::put('/laboratory/{laboratory}/validate', [LaboratoryController::class, 'validateAnalysis'])->name('laboratory.validate');
    Route::resource('pharmacy', PharmacyController::class);
    Route::post('/pharmacy/{pharmacy}/dispense', [PharmacyController::class, 'dispense'])->name('pharmacy.dispense');
    Route::post('/pharmacy/{pharmacy}/restock', [PharmacyController::class, 'restock'])->name('pharmacy.restock');

    // Document export routes
    Route::get('/patients/{patient}/export', [DocumentExportController::class, 'showExportInterface'])->name('patients.export');
    Route::get('/patients/{patient}/export/medical-record', [DocumentExportController::class, 'exportPatientMedicalRecord'])->name('patients.export.medical-record');
    Route::get('/patients/{patient}/export/summary', [DocumentExportController::class, 'exportPatientSummary'])->name('patients.export.summary');
    Route::get('/consultations/{consultation}/export/prescription', [DocumentExportController::class, 'exportPrescription'])->name('consultations.export.prescription');
    Route::get('/consultations/{consultation}/export/report', [DocumentExportController::class, 'exportConsultationReport'])->name('consultations.export.report');
    Route::get('/hospitalizations/{hospitalization}/export/report', [DocumentExportController::class, 'exportHospitalizationReport'])->name('hospitalizations.export.report');
    Route::get('/analysis/{analysis}/export/report', [DocumentExportController::class, 'exportLaboratoryReport'])->name('analysis.export.report');
    Route::get('/patients/{patient}/export/prescription-history', [DocumentExportController::class, 'exportPrescriptionHistory'])->name('patients.export.prescription-history');
    Route::get('/patients/{patient}/export/audit', [DocumentExportController::class, 'exportPatientAudit'])->name('patients.export.audit');
    Route::get('/documents/verify/{documentId}', [DocumentExportController::class, 'verifyDocument'])->name('documents.verify');
});
