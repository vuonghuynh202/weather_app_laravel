<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherSubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [WeatherController::class, 'index']);

Route::get('/weather', [WeatherController::class, 'getWeather']);
Route::post('/weather/clear-history', [WeatherController::class, 'clearHistory'])->name('weather.clear-history');
Route::get('/history/{location}', [WeatherController::class, 'historyDetails']);

Route::post('/subscribe', [WeatherSubscriptionController::class, 'subscribe'])->name('subscribe');
Route::get('/confirm/{token}', [WeatherSubscriptionController::class, 'confirmEmail'])->name('confirm.email');
Route::get('/unsubscribe/{email}', [WeatherSubscriptionController::class, 'unsubscribe'])->name('unsubscribe');




