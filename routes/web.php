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

Route::get('/catalogos', function () {
    return view('catalogos');
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
Route::get('/catalogos', 'CatalogosController@index')->name('catalogos');

//Route::get('/users', [HomeController::class,'getUsers']);

Route::get('/report', 'HomeController@getReport')->name('report');
Route::get('/report_by_period', 'HomeController@getReportByPeriod')->name('report_by_period');
Route::get('/get_capitulos', 'HomeController@getCapitulos')->name('get_capitulos');


Route::post('/upload_report', 'CargaController@uploadReport')->name('upload_report');
Route::post('/edit_records', 'CargaController@edit_records')->name('edit_records');
Route::post('/save_new_partidas', 'CargaController@saveNewPartidas')->name('save_new_partidas');
Route::post('/save_new_provedores', 'CargaController@saveNewProveedores')->name('save_new_provedores');
Route::post('/save_new_clue', 'CargaController@saveNewClues')->name('save_new_clue');

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
Route::get('/get_programacion_rubros', 'ProgramacionController@getProgramacionRubros')->name('get_programacion_rubros');
Route::delete('/delete_programacion_rubro/{id}', 'ProgramacionController@deleteProgramacionRubro')->name('delete_programacion_rubro');
Route::get('/get_programacion_partidas', 'ProgramacionController@getProgramacionPartidas')->name('get_programacion_partidas');
Route::get('/get_partidas', 'ProgramacionController@getPartidas')->name('get_partidas');

//Catalogos
Route::post('/save_edited_partidas', 'CatalogosController@saveEditedPartidas')->name('save_edited_partidas');
Route::get('/get_partidas', 'CatalogosController@getPartidas')->name('get_partidas');
Route::post('/save_new_partida', 'CatalogosController@saveNewPartida')->name('save_new_partida');
Route::delete('/delete_partida/{id}', 'CatalogosController@deletePartida')->name('delete_partida');