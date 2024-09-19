<?php

//Eliminar
Route::get('/', function () {
    return view('welcome');
});

//Carga page
Route::get('/carga', function () {
    return view('carga');
});

//Contratos
Route::get('/contratos', function () {
    return view('contratos');
});

Route::get('/programacion', function () {
    return view('programacion');
});


Route::get('/users/{any}', function () {
    return view('newuser');  // Carga la misma vista para todas las subrutas de /users
})->where('any', '.*');


Route::get('/users', function () {
    return view('newuser');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/carga', 'CargaController@index')->name('carga');
Route::get('/contratos', 'ContratosController@index')->name('contratos');
Route::get('/programacion', 'ProgramacionController@index')->name('programacion');

//Route::get('/users', [HomeController::class,'getUsers']);

Route::get('/report', 'HomeController@getReport')->name('report');
Route::get('/report_by_period', 'HomeController@getReportByPeriod')->name('report_by_period');


Route::post('/upload_report', 'CargaController@uploadReport')->name('upload_report');
Route::post('/edit_records', 'CargaController@edit_records')->name('edit_records');

Route::post('/register', 'UsersController@createNewUser')->name('register');
Route::get('/getusers', 'UsersController@getUsers')->name('users');
Route::put('/updateuser/{id}', 'UsersController@updateUser')->name('updateuser');
Route::delete('/deleteuser/{id}', 'UsersController@deleteUser')->name('deleteuser');

//Programacion
Route::post('/save_programacion_partidas', 'ProgramacionController@saveProgramacionPartidas')->name('save_programacion_partidas');
Route::get('/get_partidas_programadas', 'ProgramacionController@getPartidasProgramadas')->name('get_partidas_programadas');
Route::delete('/delete_partida_programada/{id}', 'ProgramacionController@deletePartidaProgramada')->name('delete_partida_programada');
Route::post('/edit_partida_programada', 'ProgramacionController@editPartidaProgramada')->name('edit_partida_programada');
Route::post('/generate_programacion_rubros', 'ProgramacionController@generateProgramacionRubros')->name('generate_programacion_rubros');
Route::get('/get_rubros', 'ProgramacionController@getRubros')->name('get_rubros');