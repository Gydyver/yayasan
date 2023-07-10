<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PointAspectController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\UploadPayReceiptController;
use App\Http\Controllers\PaymentController;


use App\Http\Controllers\Superadmin\StudentController as SuperadminStudentController;
use App\Http\Controllers\Superadmin\TeacherController as SuperadminTeacherController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', 'DashboardController@index')->name('dashboard.index');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/layout', function () {
    return view('app/layout');
});
// Route::get('/users', function (Request $request) {
//     // ...
// });
// Route::get('dashboard', 'App\Http\Controllers\CustomAuthController@dashboard'); 
// Route::get('login', 'App\Http\Controllers\CustomAuthController@index')->name('login');
// Route::post('custom-login', 'App\Http\Controllers\CustomAuthController@customLogin')->name('login.custom'); 
// Route::get('registration', 'App\Http\Controllers\CustomAuthController@registration')->name('register-user');
// Route::post('custom-registration', 'App\Http\Controllers\CustomAuthController@customRegistration')->name('register.custom'); 
// Route::get('signout', 'App\Http\Controllers\CustomAuthController@signOut')->name('signout');

// Route::group(['middleware' => ['guest']], function() {
/**
 * Register Routes
 */
// Route::get('/register', 'RegisterController@show')->name('register.show');
// Route::post('/register', 'RegisterController@register')->name('register.perform');

/**
 * Login Routes
 */
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login.view');
    Route::post('/login', 'login')->name('login.perform');
});

// });



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

Route::controller(UserGroupController::class)->group(function () {
    Route::get('/usergroup', 'index')->name('usergroup.index');
    Route::get('usergroup/list', 'getDatatable')->name('usergroup.list');
    Route::get('/usergroup/destroy/{id}', 'destroy')->name('usergroup.destroy');
    Route::post('/usergroup/create', 'store')->name('usergroup.store');
    Route::get('/usergroup/show', 'show')->name('usergroup.show');
    Route::get('/usergroup/edit', 'edit')->name('usergroup.edit');
    Route::post('/usergroup/update', 'update')->name('usergroup.update');
});



Route::group(['middleware' => ['auth']], function () {


    Route::controller(ClassController::class)->group(function () {
        Route::get('/class', 'index')->name('class.index');
        Route::get('/class/list', 'getDatatable')->name('class.list');
        Route::get('/class/destroy/{id}', 'destroy')->name('class.destroy');
        Route::post('/class/create', 'store')->name('class.store');
        Route::get('/class/show/{idEncrypted}', 'show')->name('class.show');
        Route::get('/class/getDatatableSession/list/{id}', 'getDatatableSession')->name('class.session.list');
        Route::get('/class/showSessionGenerated/{idEncrypted}', 'showSessionGenerated')->name('class.showSessionGenerated');
        Route::post('/class/updateSessionGenerated', 'updateSessionGenerated')->name('class.sessionGenerated.update');
        Route::post('/class/updateSessionGeneratedStat', 'updateSessionGeneratedStat')->name('class.sessionGenerated.updateStat');
        Route::get('/class/sessionGenerated/destroy/{id}', 'destroySessionGenerated')->name('class.sessionGenerated.destroy');
        // Route::get('/class/getDatatableSesGenerated/list/{id}', 'getDatatableSessionGenerated')->name('class.sessionGenerated.list');
        Route::get('/class/edit', 'edit')->name('class.edit');
        Route::post('/class/update', 'update')->name('class.update');
        Route::post('/class/close', 'close')->name('class.close');
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

    Route::controller(UserAccessController::class)->group(function () {
        Route::get('/useraccess', 'index')->name('useraccess.index');
        Route::get('useraccess/list', 'getDatatable')->name('useraccess.list');
        Route::get('/useraccess/destroy/{id}', 'destroy')->name('useraccess.destroy');
        Route::get('/useraccess/detail/{id}', 'detail')->name('useraccess.detail');
        Route::get('/useraccess/edit', 'edit')->name('useraccess.edit');
        Route::post('/useraccess/update', 'update')->name('useraccess.update');
    });

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
        Route::get('/point_aspect', 'index')->name('point_aspect.index');
        Route::get('point_aspect/list', 'getDatatable')->name('point_aspect.list');
        Route::get('/point_aspect/destroy/{id}', 'destroy')->name('point_aspect.destroy');
        Route::post('/point_aspect/create', 'store')->name('point_aspect.store');
        Route::get('/point_aspect/show', 'show')->name('point_aspect.show');
        Route::get('/point_aspect/edit', 'edit')->name('point_aspect.edit');
        Route::post('/point_aspect/update', 'update')->name('point_aspect.update');
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

    Route::controller(SessionController::class)->group(function () {
        Route::get('/session', 'index')->name('session.index');
        Route::get('/session/list', 'getDatatable')->name('session.list');
        Route::get('/session/destroy/{id}', 'destroy')->name('session.destroy');
        Route::post('/session/create', 'store')->name('session.store');
        Route::get('/session/showSession/{idEncrypted}', 'showSession')->name('session.show');
        Route::get('/session/getDatatableSesGenerated/list/{idEncrypted}', 'getDatatableSessionGenerated')->name('session.session_generated.list');
        Route::get('/session/edit', 'edit')->name('session.edit');
        Route::post('/session/update', 'update')->name('session.update');
        Route::post('/session/generate', 'generate')->name('session.generate');
    });

    Route::controller(BillingController::class)->group(function () {
        Route::get('/billing', 'index')->name('billing.index');
        Route::get('/billing/list', 'getDatatable')->name('billing.list');
        Route::get('/billing/destroy/{id}', 'destroy')->name('billing.destroy');
        Route::post('/billing/create', 'store')->name('billing.store');
        Route::get('/billing/show/{idEncrypted}', 'show')->name('billing.show');
        Route::get('/billing/edit', 'edit')->name('billing.edit');
        Route::post('/billing/update', 'update')->name('billing.update');
        Route::post('/billing/generateMonthlyBilling', 'generateMonthlyBilling')->name('billing.generateMonthlyBilling');
    });

    Route::controller(SuperadminStudentController::class)->group(function () {
        Route::get('/superadmin/student', 'index')->name('superadmin.student.index');
        Route::get('/superadmin/student/list', 'getDatatable')->name('superadmin.student.list');
        Route::post('/superadmin/student/changeChapter', 'changeChapter')->name('superadmin.student.changeChapter');
        Route::post('/superadmin/student/setChapter', 'setChapter')->name('superadmin.student.setChapter');
        Route::post('/superadmin/student/changeClass', 'changeClass')->name('superadmin.student.changeClass');
        Route::post('/superadmin/student/setClass', 'setClass')->name('superadmin.student.setClass');
        Route::get('/superadmin/student/show/{idEncrypted}', 'show')->name('superadmin.student.show');
    });

    Route::controller(SuperadminTeacherController::class)->group(function () {
        Route::get('/superadmin/teacher', 'index')->name('superadmin.teacher.index');
        Route::get('/superadmin/teacher/list', 'getDatatable')->name('superadmin.teacher.list');
        Route::get('/superadmin/teacher/show/{idEncrypted}', 'show')->name('superadmin.teacher.show');
        Route::get('/superadmin/teacher/show/list/{idEncrypted}', 'getDatatableSession')->name('superadmin.teacher.session.list');
    });

    Route::controller(UploadPayReceiptController::class)->group(function () {
        Route::get('/upload_payreceipt', 'index')->name('upload_payreceipt.index');
        // Route::get('/upload_payreceipt/unpaid/list', 'getDatatableUnpaid')->name('upload_payreceipt.unpaid.list');
        // Route::get('/upload_payreceipt/paid/list', 'getDatatablePaid')->name('upload_payreceipt.paid.list');
        Route::get('/upload_payreceipt/list', 'getDatatable')->name('upload_payreceipt.list');
        Route::get('/upload_payreceipt/destroy/{id}', 'destroy')->name('upload_payreceipt.destroy');
        Route::post('/upload_payreceipt/uploadFile', 'uploadFile')->name('upload_payreceipt.uploadFile');
        Route::get('/upload_payreceipt/formUploadBulking', 'formUploadBulking')->name('upload_payreceipt.formUploadFile');
        Route::post('/upload_payreceipt/uploadFileBulking', 'uploadFileBulking')->name('upload_payreceipt.uploadFileBulking');
        Route::get('/upload_payreceipt/show', 'show')->name('upload_payreceipt.show');
        Route::get('/upload_payreceipt/edit', 'edit')->name('upload_payreceipt.edit');
        Route::post('/upload_payreceipt/update', 'update')->name('upload_payreceipt.update');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payment', 'index')->name('payment.index');
        Route::get('/payment/list', 'getDatatable')->name('payment.list');
        Route::get('/payment/show/{idEncrypted}', 'show')->name('payment.show');
        Route::get('/payment/destroy/{id}', 'destroy')->name('payment.destroy');
        Route::post('/payment/update/{idEncrypted}', 'update')->name('payment.updateFile');
        Route::get('/payment/edit/{idEncrypted}', 'edit')->name('payment.edit');
        Route::get('/payment/detail/list/{idEncrypted}', 'getDatatablePaymentDetail')->name('payment.detail.list');
        Route::get('/payment/status_update/{idEncrypted}', 'statusUpdate')->name('payment.status_update');
        Route::get('/payment/edit/{idEncrypted}', 'edit')->name('payment.edit');
    });

    Route::controller(TeacherClassController::class)->group(function () {
        Route::get('/teacher/class', 'index')->name('teacher.class.index');
        Route::get('/teacher/class/list', 'getDatatable')->name('teacher.class.list');
        Route::get('/teacher/class/destroy/{id}', 'destroy')->name('teacherclass.destroy');
        Route::post('/teacher/class/create', 'store')->name('teacherclass.store');
        Route::get('/teacher/class/session/{idEncrypted}', 'showSession')->name('teacherclass.session');
        Route::get('/teacher/class/session/list/{idEncrypted}', 'getDatatableSession')->name('teacherclass.Session.list');
        Route::get('/teacher/class/{idEncryptedClass}/session/point/{idEncrypted}', 'showSessionStudent')->name('teacherclass.session.point');
        Route::get('/teacher/class/session/point/list/{idEncrypted}', 'getDatatableSessionPointHistory')->name('teacherclass.Session.point.list');
        Route::get('/teacher/class/session/getPointAspectStudent/{id}', 'getPointAspectStudent')->name('teacherclass.session.getPointAspectStudent');
        Route::post('/teacher/class/session/history/point/create', 'createHistoryPoint')->name('teacherclass.session.history.store');
    });

    Route::controller(TeacherStudentController::class)->group(function () {
        Route::get('/teacher/student', 'index')->name('teacher.student.index');
        Route::get('/teacher/student/list', 'getDatatable')->name('teacher.student.list');
        Route::get('/teacher/class/destroy/{id}', 'destroy')->name('teacherclass.destroy');
        Route::post('/teacher/class/create', 'store')->name('teacherclass.store');
        Route::get('/teacher/class/session/{idEncrypted}', 'showSession')->name('teacherclass.session');
        // Route::get('/teacher/class/session/list/{idEncrypted}', 'getDatatableSession')->name('teacher.classSession.list');

        Route::get('/teacher/class/show/{idEncrypted}', 'show')->name('teacherclass.show');
        Route::get('/teacher/class/edit', 'edit')->name('teacherclass.edit');
        Route::post('/teacher/class/update', 'update')->name('teacherclass.update');
    });

    /**
     * Logout Routes
     */
    Route::get('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
});
