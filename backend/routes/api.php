<?php

use App\Http\Controllers\AccAccountController;
use App\Http\Controllers\AccObjectController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountOriginController;
use App\Http\Controllers\AccTransactionController;
use App\Http\Controllers\AccTransDetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustoemrController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\RealtyController;
use App\Http\Controllers\RealtyOwnerController;
use App\Http\Controllers\RealtyUnitController;
use App\Http\Controllers\RealtyUnitReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('doc', DocController::class);

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    // Route::post('send-reset-code', [AuthController::class, 'sendResetCode']);
    // Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('account', AccountController::class);
    Route::apiResource('account-origin', AccountOriginController::class);
    Route::apiResource('acc-account', AccAccountController::class);

    Route::apiResource('acc-transaction', AccTransactionController::class);
    Route::apiResource('acc-trans-detail', AccTransDetailController::class);
    Route::apiResource('realty', RealtyController::class);
    Route::apiResource('realty-unit', RealtyUnitController::class);
    Route::apiResource('realty-unit-reservation', RealtyUnitReservationController::class);
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('people', PeopleController::class);
    Route::apiResource('acc-object', AccObjectController::class);

    Route::apiResource('owner', OwnerController::class);
    Route::post('test', [AuthController::class, 'test']);
});
