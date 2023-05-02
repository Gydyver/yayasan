<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PointAspectController;
use App\Http\Controllers\SessionController;

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

Route::get('/layout', function () {
    return view('app/layout');
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

// Route::resource('class', 'App\Http\Controllers\ClassController');

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


// Route::group(['prefix' => 'users'], function() {
//     Route::get('/', 'UsersController@index')->name('users.index');
//     Route::get('/create', 'UsersController@create')->name('users.create');
//     Route::post('/create', 'UsersController@store')->name('users.store');
//     Route::get('/{user}/show', 'UsersController@show')->name('users.show');
//     Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
//     Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
//     Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
// });

// Route::group(['namespace' => '/class_type'], function() {
    
Route::controller(ClassController::class)->group(function () {
    Route::get('/class', 'index')->name('class.index');
    Route::get('/class/list', 'getDatatable')->name('class.list');
    Route::get('/class/destroy/{id}', 'destroy')->name('class.destroy');
    Route::post('/class/create', 'store')->name('class.store');
    Route::get('/class/show/{idEncrypted}', 'show')->name('class.show');
    Route::get('/class/edit', 'edit')->name('class.edit');
    Route::post('/class/update', 'update')->name('class.update');
});
    

Route::controller(ClassTypeController::class)->group(function () {
    Route::get('/class_type', 'index')->name('class_type.index');
    Route::get('/class_type/list', 'getDatatable')->name('class_type.list');
    Route::get('/class_type/destroy/{id}', 'destroy')->name('class_type.destroy');
    Route::post('/class_type/create', 'store')->name('class_type.store');
    Route::get('/class_type/show', 'show')->name('class_type.show');
    Route::get('/class_type/edit', 'edit')->name('class_type.edit');
    Route::post('/class_type/update', 'update')->name('class_type.update');
});

Route::controller(ChapterController::class)->group(function () {
    Route::get('/chapter', 'index')->name('chapter.index');
    Route::get('chapter/list', 'getDatatable')->name('chapter.list');
    Route::get('/chapter/destroy/{id}', 'destroy')->name('chapter.destroy');
    Route::post('/chapter/create', 'store')->name('chapter.store');
    Route::post('/chapter/addPointAspect', 'addPointAspect')->name('chapter.addPointAspect');
    Route::get('/chapter/show', 'show')->name('chapter.show');
    Route::get('/chapter/edit', 'edit')->name('chapter.edit');
    Route::post('/chapter/update', 'update')->name('chapter.update');
});

Route::controller(UserGroupController::class)->group(function () {
    Route::get('/usergroup', 'index')->name('usergroup.index');
    Route::get('usergroup/list', 'getDatatable')->name('usergroup.list');
    Route::get('/usergroup/destroy/{id}', 'destroy')->name('usergroup.destroy');
    Route::post('/usergroup/create', 'store')->name('usergroup.store');
    Route::get('/usergroup/show', 'show')->name('usergroup.show');
    Route::get('/usergroup/edit', 'edit')->name('usergroup.edit');
    Route::post('/usergroup/update', 'update')->name('usergroup.update');
});


// Route::controller(UserAccessController::class)->group(function () {
//     Route::get('/useraccess', 'index')->name('useraccess.index');
//     Route::get('useraccess/list', 'getDatatable')->name('useraccess.list');
//     Route::get('/useraccess/destroy/{id}', 'destroy')->name('useraccess.destroy');
//     Route::post('/useraccess/create', 'store')->name('useraccess.store');
//     Route::get('/useraccess/show', 'show')->name('useraccess.show');
//     Route::get('/useraccess/edit', 'edit')->name('useraccess.edit');
//     Route::post('/useraccess/update', 'update')->name('useraccess.update');
// });

Route::controller(GradeController::class)->group(function () {
    Route::get('/grade', 'index')->name('grade.index');
    Route::get('grade/list', 'getDatatable')->name('grade.list');
    Route::get('/grade/destroy/{id}', 'destroy')->name('grade.destroy');
    Route::post('/grade/create', 'store')->name('grade.store');
    Route::get('/grade/show', 'show')->name('grade.show');
    Route::get('/grade/edit', 'edit')->name('grade.edit');
    Route::post('/grade/update', 'update')->name('grade.update');
});

Route::controller(PointAspectController::class)->group(function () {
    Route::get('/point_aspect','index')->name('point_aspect.index');
    Route::get('point_aspect/list','getDatatable')->name('point_aspect.list');
    Route::get('/point_aspect/destroy/{id}','destroy')->name('point_aspect.destroy');
    Route::post('/point_aspect/create','store')->name('point_aspect.store');
    Route::get('/point_aspect/show','show')->name('point_aspect.show');
    Route::get('/point_aspect/edit','edit')->name('point_aspect.edit');
    Route::post('/point_aspect/update','update')->name('point_aspect.update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('user.index');
    Route::get('user/list', 'getDatatable')->name('user.list');
    Route::get('/user/show/{id}', 'show')->name('user.show');
    Route::get('/user/destroy/{id}', 'destroy')->name('user.destroy');
    Route::post('/user/create', 'store')->name('user.store');
    Route::get('/user/show', 'show')->name('user.show');
    Route::get('/user/edit', 'edit')->name('user.edit');
    Route::post('/user/update', 'update')->name('user.update');
});

Route::controller(MenuController::class)->group(function () {
    Route::get('/menu', 'index')->name('menu.index');
    Route::get('menu/list', 'getDatatable')->name('menu.list');
    Route::get('/menu/show/{id}', 'show')->name('menu.show');
    Route::get('/menu/destroy/{id}', 'destroy')->name('menu.destroy');
    Route::post('/menu/create', 'store')->name('menu.store');
    Route::get('/menu/show', 'show')->name('menu.show');
    Route::get('/menu/edit', 'edit')->name('menu.edit');
    Route::post('/menu/update', 'update')->name('menu.update');
});


// Route::controller(UploadPayReceiptController::class)->group(function () {
//     Route::get('/payment_receipt', 'index')->name('payment_receipt.index');
//     Route::get('/payment_receipt/list', 'getDatatable')->name('payment_receipt.list');
//     Route::get('/payment_receipt/destroy/{id}', 'destroy')->name('payment_receipt.destroy');
//     Route::post('/payment_receipt/create', 'store')->name('payment_receipt.store');
//     Route::get('/payment_receipt/show', 'show')->name('payment_receipt.show');
//     Route::get('/payment_receipt/edit', 'edit')->name('payment_receipt.edit');
//     Route::post('/payment_receipt/update', 'update')->name('payment_receipt.update');
// });

Route::controller(SessionController::class)->group(function () {
    Route::get('/session', 'index')->name('session.index');
    Route::get('/session/list', 'getDatatable')->name('session.list');
    Route::get('/session/destroy/{id}', 'destroy')->name('session.destroy');
    Route::post('/session/create', 'store')->name('session.store');
    Route::get('/session/show/{idEncrypted}', 'show')->name('session.show');
    Route::get('/session/edit', 'edit')->name('session.edit');
    Route::post('/session/update', 'update')->name('session.update');
});

Route::controller(BillingController::class)->group(function () {
    Route::get('/billing', 'index')->name('billing.index');
    Route::get('/billing/list', 'getDatatable')->name('billing.list');
    Route::get('/billing/destroy/{id}', 'destroy')->name('billing.destroy');
    Route::post('/billing/create', 'store')->name('billing.store');
    Route::get('/billing/show/{idEncrypted}', 'show')->name('billing.show');
    Route::get('/billing/edit', 'edit')->name('billing.edit');
    Route::post('/billing/update', 'update')->name('billing.update');
});