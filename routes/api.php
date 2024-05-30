<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StateController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CityController::class)->group(function (){
    Route::get('/cities', 'index');
    Route::post('/cities', 'store');
    Route::get('/cities/{id}', 'show');
    Route::put('/cities/{id}', 'update');
    Route::delete('/cities/{id}', 'destroy');
});

Route::controller(StateController::class)->group(function (){
    Route::get('/states', 'index');
    Route::post('/states', 'store');
    Route::get('/states/{id}', 'show');
    Route::put('/states/{id}', 'update');
    Route::delete('/states/{id}', 'destroy');
});
Route::controller(BankController::class)->group(function (){
    Route::get('/banks', 'index');
    Route::post('/banks', 'store');
    Route::get('/banks/{id}', 'show');
    Route::put('/banks/{id}', 'update');
    Route::delete('/banks/{id}', 'destroy');
});
Route::controller(BillController::class)->group(function (){
    Route::get('/bills', 'index');
    Route::get('/rentals', 'showRental');
    Route::get('/billsEdit', 'showBill');
    Route::get('/rentals/{id}', 'showRentalOne');
    Route::get('/getForCreate', 'getDataForCreate');
    Route::post('/bills', 'store');
    Route::post('/rentals', 'createBill');
    Route::put('/rentals/{billId}', 'updateBill');
    Route::get('/bills/{id}', 'show');
    Route::put('/bills/{id}', 'update');
    Route::delete('/bills/{id}', 'destroy');
});

Route::controller(CarController::class)->group(function (){
    Route::get('/cars', 'index');
    Route::get('/cars/search', 'search');
    Route::post('/cars', 'store');
    Route::get('/cars/{id}', 'show');
    Route::put('/cars/{id}', 'update');
    Route::delete('/cars/{id}', 'destroy');
});
Route::controller(CustomerServiceController::class)->group(function (){
    Route::get('/customer_services', 'index');
    Route::post('/customer_services', 'store');
    Route::get('/customer_services/{id}', 'show');
    Route::put('/customer_services/{id}', 'update');
    Route::delete('/customer_services/{id}', 'destroy');
});

Route::controller(FeedbackController::class)->group(function (){
    Route::get('/feedback', 'index');
    Route::post('/feedback', 'store');
    Route::get('feedback/{id}', 'show');
    Route::put('feedback/{id}', 'update');
    Route::delete('feedback/{id}', 'destroy');

});

Route::controller(RentalController::class)->group(function (){
    Route::get('/rental', 'index');
    Route::post('/rental', 'store');
    Route::get('rental/{id}', 'show');
    Route::put('rental/{id}', 'update');
    Route::delete('rental/{id}', 'destroy');

});


Route::controller(RoleController::class)->group(function (){
    Route::get('/roles', 'index');
    Route::post('/roles', 'store');
    Route::get('roles/{id}', 'show');
    Route::put('roles/{id}', 'update');
    Route::delete('roles/{id}', 'destroy');

});
Route::controller(StateController::class)->group(function (){
    Route::get('/states', 'index');
    Route::post('/states', 'store');
    Route::get('states/{id}', 'show');
    Route::put('states/{id}', 'update');
    Route::delete('states/{id}', 'destroy');

});
Route::get('/search', [SearchController::class, 'search']);





