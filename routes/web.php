<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ClassTypeController;

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

// Route::get('/users', function (Request $request) {
//     // ...
// });

 
// Route::controller(UserController::class)->group(function () {
//     Route::get('/orders/{id}', 'show');
//     Route::post('/orders', 'store');
// }); 
// Route::controller(ClassController::class)->group(function () {
//     Route::get('/orders/{id}', 'show');
//     Route::post('/orders', 'store');
// });

Route::resource('class', 'App\Http\Controllers\ClassController');

// Route::resource('class_type', 'App\Http\Controllers\ClassTypeController');

// Route::get('dashboard', [CustomAuthController::class, 'dashboard']); 
// Route::get('login', [CustomAuthController::class, 'index'])->name('login');
// Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
// Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
// Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
// Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('dashboard', 'App\Http\Controllers\CustomAuthController@dashboard'); 
Route::get('login', 'App\Http\Controllers\CustomAuthController@index')->name('login');
Route::post('custom-login', 'App\Http\Controllers\CustomAuthController@customLogin')->name('login.custom'); 
Route::get('registration', 'App\Http\Controllers\CustomAuthController@registration')->name('register-user');
Route::post('custom-registration', 'App\Http\Controllers\CustomAuthController@customRegistration')->name('register.custom'); 
Route::get('signout', 'App\Http\Controllers\CustomAuthController@signOut')->name('signout');

Route::group(['prefix' => 'users'], function() {
    Route::get('/', 'UsersController@index')->name('users.index');
    Route::get('/create', 'UsersController@create')->name('users.create');
    Route::post('/create', 'UsersController@store')->name('users.store');
    Route::get('/{user}/show', 'UsersController@show')->name('users.show');
    Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
    Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
});

// Route::group(['namespace' => '/class_type'], function() {
    Route::get('/class_type', 'App\Http\Controllers\ClassTypeController@index')->name('class_type.index');
    Route::get('class_type/list', 'App\Http\Controllers\ClassTypeController@getDatatable')->name('class_type.list');
    Route::get('/class_type/destroy/{id}', 'App\Http\Controllers\ClassTypeController@destroy')->name('class_type.destroy');
    Route::post('/class_type/create', 'App\Http\Controllers\ClassTypeController@store')->name('class_type.store');
    Route::get('/class_type/show', 'App\Http\Controllers\ClassTypeController@show')->name('class_type.show');
    Route::get('/class_type/edit', 'App\Http\Controllers\ClassTypeController@edit')->name('class_type.edit');
    Route::post('/class_type/update', 'App\Http\Controllers\ClassTypeController@update')->name('class_type.update');
// });


Route::get('/chapter', 'App\Http\Controllers\ChapterController@index')->name('chapter.index');
Route::get('chapter/list', 'App\Http\Controllers\ChapterController@getDatatable')->name('chapter.list');
Route::get('/chapter/destroy/{id}', 'App\Http\Controllers\ChapterController@destroy')->name('chapter.destroy');
Route::post('/chapter/create', 'App\Http\Controllers\ChapterController@store')->name('chapter.store');
Route::get('/chapter/show', 'App\Http\Controllers\ChapterController@show')->name('chapter.show');
Route::get('/chapter/edit', 'App\Http\Controllers\ChapterController@edit')->name('chapter.edit');
Route::post('/chapter/update', 'App\Http\Controllers\ChapterController@update')->name('chapter.update');

Route::get('/usergroup', 'App\Http\Controllers\UserGroupController@index')->name('usergroup.index');
Route::get('usergroup/list', 'App\Http\Controllers\UserGroupController@getDatatable')->name('usergroup.list');
Route::get('/usergroup/destroy/{id}', 'App\Http\Controllers\UserGroupController@destroy')->name('usergroup.destroy');
Route::post('/usergroup/create', 'App\Http\Controllers\UserGroupController@store')->name('usergroup.store');
Route::get('/usergroup/show', 'App\Http\Controllers\UserGroupController@show')->name('usergroup.show');
Route::get('/usergroup/edit', 'App\Http\Controllers\UserGroupController@edit')->name('usergroup.edit');
Route::post('/usergroup/update', 'App\Http\Controllers\UserGroupController@update')->name('usergroup.update');

Route::get('/grade', 'App\Http\Controllers\GradeController@index')->name('grade.index');
Route::get('grade/list', 'App\Http\Controllers\GradeController@getDatatable')->name('grade.list');
Route::get('/grade/destroy/{id}', 'App\Http\Controllers\GradeController@destroy')->name('grade.destroy');
Route::post('/grade/create', 'App\Http\Controllers\GradeController@store')->name('grade.store');
Route::get('/grade/show', 'App\Http\Controllers\GradeController@show')->name('grade.show');
Route::get('/grade/edit', 'App\Http\Controllers\GradeController@edit')->name('grade.edit');
Route::post('/grade/update', 'App\Http\Controllers\GradeController@update')->name('grade.update');

Route::get('/point_aspect', 'App\Http\Controllers\PointAspectController@index')->name('point_aspect.index');
Route::get('point_aspect/list', 'App\Http\Controllers\PointAspectController@getDatatable')->name('point_aspect.list');
Route::get('/point_aspect/destroy/{id}', 'App\Http\Controllers\PointAspectController@destroy')->name('point_aspect.destroy');
Route::post('/point_aspect/create', 'App\Http\Controllers\PointAspectController@store')->name('point_aspect.store');
Route::get('/point_aspect/show', 'App\Http\Controllers\PointAspectController@show')->name('point_aspect.show');
Route::get('/point_aspect/edit', 'App\Http\Controllers\PointAspectController@edit')->name('point_aspect.edit');
Route::post('/point_aspect/update', 'App\Http\Controllers\PointAspectController@update')->name('point_aspect.update');

