<?php
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/carga', function () {
    return view('carga');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/users', [HomeController::class,'getUsers']);

Route::get('/report', 'HomeController@getReport')->name('report');
Route::get('/report_by_period', 'HomeController@getReportByPeriod')->name('report_by_period');

