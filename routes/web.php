<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EnrollmentController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);


Route::get('/enrollments', [EnrollmentController::class, 'index']);
Route::post('/enrollments', [EnrollmentController::class, 'store']);

Route::post('/courses', [EnrollmentController::class, 'addCourse']);