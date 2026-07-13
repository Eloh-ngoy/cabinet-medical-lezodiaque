<?php

use App\Http\Controllers\Api\ApiConsultationController;
use App\Http\Controllers\Api\ApiHospitalizationController;
use App\Http\Controllers\Api\ApiLaboratoryController;
use App\Http\Controllers\Api\ApiPatientController;
use App\Http\Controllers\Api\ApiPharmacyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:web')->group(function () {
    // Patients
    Route::apiResource('patients', ApiPatientController::class)
        ->names('api.patients');

    // Consultations
    Route::apiResource('consultations', ApiConsultationController::class);

    // Hospitalizations
    Route::apiResource('hospitalizations', ApiHospitalizationController::class);
    Route::put('hospitalizations/{hospitalization}/discharge', [ApiHospitalizationController::class, 'discharge']);

    // Laboratory
    Route::get('laboratory', [ApiLaboratoryController::class, 'index']);
    Route::post('laboratory', [ApiLaboratoryController::class, 'store']);
    Route::get('laboratory/{laboratoryAnalysis}', [ApiLaboratoryController::class, 'show']);
    Route::put('laboratory/{laboratoryAnalysis}/results', [ApiLaboratoryController::class, 'enterResults']);
    Route::put('laboratory/{laboratoryAnalysis}/validate', [ApiLaboratoryController::class, 'validateAnalysis']);

    // Pharmacy
    Route::get('pharmacy', [ApiPharmacyController::class, 'index']);
    Route::post('pharmacy', [ApiPharmacyController::class, 'store']);
    Route::get('pharmacy/{medication}', [ApiPharmacyController::class, 'show']);
    Route::put('pharmacy/{medication}', [ApiPharmacyController::class, 'update']);
    Route::post('pharmacy/{medication}/dispense', [ApiPharmacyController::class, 'dispense']);
    Route::post('pharmacy/{medication}/restock', [ApiPharmacyController::class, 'restock']);
});
