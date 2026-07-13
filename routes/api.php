<?php

use App\Http\Controllers\Api\ApiConsultationController;
use App\Http\Controllers\Api\ApiHospitalizationController;
use App\Http\Controllers\Api\ApiLaboratoryController;
use App\Http\Controllers\Api\ApiPatientController;
use App\Http\Controllers\Api\ApiPharmacyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('auth:web')
    ->name('api.v1.')
    ->group(function () {

        // Patients
        Route::apiResource('patients', ApiPatientController::class);

        // Consultations
        Route::apiResource('consultations', ApiConsultationController::class);

        // Hospitalizations
        Route::apiResource('hospitalizations', ApiHospitalizationController::class);

        Route::put(
            'hospitalizations/{hospitalization}/discharge',
            [ApiHospitalizationController::class, 'discharge']
        )->name('hospitalizations.discharge');


        // Laboratory
        Route::get('laboratory', [ApiLaboratoryController::class, 'index'])
            ->name('laboratory.index');

        Route::post('laboratory', [ApiLaboratoryController::class, 'store'])
            ->name('laboratory.store');

        Route::get('laboratory/{laboratoryAnalysis}', [ApiLaboratoryController::class, 'show'])
            ->name('laboratory.show');

        Route::put('laboratory/{laboratoryAnalysis}/results', [ApiLaboratoryController::class, 'enterResults'])
            ->name('laboratory.results');

        Route::put('laboratory/{laboratoryAnalysis}/validate', [ApiLaboratoryController::class, 'validateAnalysis'])
            ->name('laboratory.validate');


        // Pharmacy
        Route::get('pharmacy', [ApiPharmacyController::class, 'index'])
            ->name('pharmacy.index');

        Route::post('pharmacy', [ApiPharmacyController::class, 'store'])
            ->name('pharmacy.store');

        Route::get('pharmacy/{medication}', [ApiPharmacyController::class, 'show'])
            ->name('pharmacy.show');

        Route::put('pharmacy/{medication}', [ApiPharmacyController::class, 'update'])
            ->name('pharmacy.update');

        Route::post('pharmacy/{medication}/dispense', [ApiPharmacyController::class, 'dispense'])
            ->name('pharmacy.dispense');

        Route::post('pharmacy/{medication}/restock', [ApiPharmacyController::class, 'restock'])
            ->name('pharmacy.restock');
    });