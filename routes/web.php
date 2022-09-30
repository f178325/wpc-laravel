<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CpanelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostController;
use Illuminate\Support\Facades\Route;

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
Route::get('/login', [AuthController::class, 'getLogin'])->name('getLogin');
Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
Route::get('logout', [AuthController::class, 'postLogout'])->name('postLogout');

Route::group(['middleware' => 'auth'], static function () {
    Route::get('/', [HomeController::class, 'getDashboard'])->name('getDashboard');
    Route::post('/delete', [HomeController::class, 'postDelete'])->name('postDelete');

    Route::controller(HostController::class)->group(function () {
        Route::get('/servers', 'getHosts')->name('getHosts');
        Route::post('/servers', 'postHosts')->name('postHosts');
        Route::post('/get-subdomains', 'getSubdomains')->name('getSubdomains');


    });

    Route::controller(CpanelController::class)->group(function () {
        Route::post('/get-table', 'getTable')->name('getTable');
        Route::get('functions/domain-backup', 'getBackup')->name('getBackup');

        Route::get('/terminate', 'getTerminate')->name('getTerminate');
        Route::post('/terminate', 'postTerminate')->name('postTerminate');

//        Routes related to email functions
        Route::get('/bulk-create-emails', 'getEmails')->name('getEmails');
        Route::post('/create-emails', 'postEmails')->name('postEmails');
        Route::get('/create-forwarder', 'getEmailF')->name('getEmailF');
        Route::post('/create-forwarder', 'postForwarder')->name('postForwarder');

//        Routes related to database function
        Route::get('functions/reconcile-db', 'getReconcile')->name('getReconcile');
        Route::post('functions/reconcile-db', 'postReconcile')->name('postReconcile');
        Route::post('/get-database', 'getDatabase')->name('getDatabase');


//        Routes related to repository functions
        Route::get('/file-repository', 'getRepository')->name('getRepository');
        Route::post('/file-repository', 'getRepoList')->name('getRepoList');
        Route::post('/delete-file', 'postDeleteFile')->name('postDeleteFile');
        Route::post('/create-directory', 'createDirectory')->name('createDirectory');
        Route::post('/remove-directory', 'removeDirectory')->name('removeDirectory');
        Route::post('/upload-file', 'uploadFile')->name('uploadFile');
    });
});

Route::get('/convert', [AuthController::class, 'convert']);
