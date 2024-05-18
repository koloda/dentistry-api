<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'success'];
});

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
    Route::get('/patients/{patient}', [\App\Http\Controllers\Patient\PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients', [\App\Http\Controllers\Patient\PatientController::class, 'list'])->name('patients.list');

    Route::get('/appointments', [\App\Http\Controllers\Appointment\AppointmentController::class, 'list'])->name('appointments.list');
    Route::post('/appointments', [\App\Http\Controllers\Appointment\AppointmentController::class, 'add'])->name('appointments.add');
    Route::post('/appointments/{appointment}/cancel', [\App\Http\Controllers\Appointment\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/appointments/{appointment}/move', [\App\Http\Controllers\Appointment\AppointmentController::class, 'move'])->name('appointments.move');
    Route::get('/appointments/agenda', [\App\Http\Controllers\Appointment\AppointmentController::class, 'agenda'])->name('appointments.agenda');
    Route::post('/appointments/{appointment}/complete', [\App\Http\Controllers\Appointment\AppointmentController::class, 'complete'])->name('appointments.complete');
});
