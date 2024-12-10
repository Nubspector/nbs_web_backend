<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\EmployeeController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\LaporanController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\TreatmentController;
use App\Http\Controllers\api\PromoController;
use App\Http\Controllers\api\RoomController;
use App\Http\Controllers\api\ShiftController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\ScheduleController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:customer-api,employee-api');
Route::get('/cekLogin',[AuthController::class, 'cekLogin'])->middleware('auth:customer-api,employee-api');

Route::prefix('employee')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [EmployeeController::class, 'index']); // Get All Employees with pagination
    Route::post('/', [EmployeeController::class, 'store']); // Create Employee
    Route::get('/{id}', [EmployeeController::class, 'show']); // Get Employee by ID
    Route::put('/{id}', [EmployeeController::class, 'update']); // Update Employee
    Route::delete('/{id}', [EmployeeController::class, 'destroy']); // Delete Employee
});

Route::prefix('customer')->middleware(['auth:employee-api','cs'])->group(function () {
    Route::get('/', [CustomerController::class, 'index']); // Get All Customers with pagination
    Route::post('/', [CustomerController::class, 'store']); // Create Customer
    Route::get('/{id}', [CustomerController::class, 'show']); // Get Customer by ID
    Route::put('/{id}', [CustomerController::class, 'update']); // Update Customer
    Route::delete('/{id}', [CustomerController::class, 'destroy']); // Delete Customer
});

Route::prefix('product')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [ProductController::class, 'index']); // Get All Products with pagination
    Route::post('/', [ProductController::class, 'store']); // Create Product
    Route::get('/{id}', [ProductController::class, 'show']); // Get Product by ID
    Route::put('/{id}', [ProductController::class, 'update']); // Update Product
    Route::delete('/{id}', [ProductController::class, 'destroy']); // Delete Product
});

Route::prefix('treatment')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [TreatmentController::class, 'index']); // Get All Treatments with pagination
    Route::post('/', [TreatmentController::class, 'store']); // Create Treatment
    Route::get('/{id}', [TreatmentController::class, 'show']); // Get Treatment by ID
    Route::put('/{id}', [TreatmentController::class, 'update']); // Update Treatment
    Route::delete('/{id}', [TreatmentController::class, 'destroy']); // Delete Treatment
});

Route::prefix('promo')->middleware(['auth:employee-api','headClinic'])->group(function () {
    Route::get('/', [PromoController::class, 'index']); // Get All Promos with pagination
    Route::post('/', [PromoController::class, 'store']); // Create Promo
    Route::get('/{id}', [PromoController::class, 'show']); // Get Promo by ID
    Route::put('/{id}', [PromoController::class, 'update']); // Update Promo
    Route::delete('/{id}', [PromoController::class, 'destroy']); // Delete Promo
});

Route::prefix('room')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [RoomController::class, 'index']); // Get All Rooms with pagination
    Route::post('/', [RoomController::class, 'store']); // Create Room
    Route::get('/{id}', [RoomController::class, 'show']); // Get Room by ID
    Route::put('/{id}', [RoomController::class, 'update']); // Update Room
    Route::delete('/{id}', [RoomController::class, 'destroy']); // Delete Room
});

Route::prefix('shift')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [ShiftController::class, 'index']); // Get All Shifts with pagination
    Route::post('/', [ShiftController::class, 'store']); // Create Shift
    Route::get('/{id}', [ShiftController::class, 'show']); // Get Shift by ID
    Route::put('/{id}', [ShiftController::class, 'update']); // Update Shift
    Route::delete('/{id}', [ShiftController::class, 'destroy']); // Delete Shift
});

Route::prefix('schedule')->middleware(['auth:employee-api','admin'])->group(function () {
    Route::get('/', [ScheduleController::class, 'index']); // Get All Schedules with pagination
    Route::post('/', [ScheduleController::class, 'store']); // Create Schedule
    Route::get('/{id}', [ScheduleController::class, 'show']); // Get Schedule by ID
    Route::put('/{id}', [ScheduleController::class, 'update']); // Update Schedule
    Route::delete('/{id}', [ScheduleController::class, 'destroy']); // Delete Schedule
});

Route::prefix('role')->middleware(['auth:employee-api,customer-api'])->group(function () {
    Route::get('/', [RoleController::class, 'index']); // Get All Roles with pagination
    Route::post('/', [RoleController::class, 'store']); // Create Role
    Route::get('/{id}', [RoleController::class, 'show']); // Get Role by ID
    Route::put('/{id}', [RoleController::class, 'update']); // Update Role
    Route::delete('/{id}', [RoleController::class, 'destroy']); // Delete Role
});

Route::prefix('report')->group(function () {
    Route::post('/customerBaru', [LaporanController::class, 'customerBaruPerBulan']); 
    Route::post('/pendapatan', [LaporanController::class, 'pendapatanPerBulan']);
    Route::post('/produkTerlaris', [LaporanController::class, 'produkTerlarisPerBulan']);
    Route::post('/perawatanTerlaris', [LaporanController::class, 'perawatanTerlarisPerBulan']);
    Route::post('/customerPerawatanPerDokter', [LaporanController::class, 'customerPerawatanPerDokterPerBulan']);
});