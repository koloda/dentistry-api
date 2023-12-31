<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function () {
    return ['message' => 'success'];
})->name('login');

Route::post('/login/sms', [\App\Http\Controllers\Auth\LoginController::class, 'sendSms'])->name('login.sms');
Route::post('/login/sms/verify', [\App\Http\Controllers\Auth\LoginController::class, 'verifySms']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clinic', [\App\Http\Controllers\Clinic\ClinicController::class, 'show'])->name('clinic.show');

    Route::post('/patients', [\App\Http\Controllers\Patient\PatientController::class, 'add'])->name('patients.add');
    Route::get('/patients/{id}', [\App\Http\Controllers\Patient\PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients', [\App\Http\Controllers\Patient\PatientController::class, 'list'])->name('patients.list');

    Route::post('/appointments', [\App\Http\Controllers\Appointment\AppointmentController::class, 'add'])->name('appointments.add');
    Route::post('/appointments/{id}/cancel', [\App\Http\Controllers\Appointment\AppointmentController::class, 'cancel'])->name('appointments.cancel');;
    Route::post('/appointments/{id}/move', [\App\Http\Controllers\Appointment\AppointmentController::class, 'move'])->name('appointments.move');
});
