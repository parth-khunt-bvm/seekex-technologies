<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\LoginController;
use App\Http\Controllers\backend\AuditTrailsController;
use App\Http\Controllers\backend\BucketController;
use App\Http\Controllers\backend\BallController;
use App\Http\Controllers\backend\BucketsuggestionsController;

Route::get('admin-logout', [LoginController::class, 'adminLogout'])->name('admin-logout');

$adminPrefix = "";
Route::group(['prefix' => $adminPrefix, 'middleware' => ['admin']], function() {
    Route::get('my-dashboard', [DashboardController::class, 'myDashboard'])->name('my-dashboard');

    Route::get('edit-profile', [DashboardController::class, 'editProfile'])->name('edit-profile');
    Route::post('save-profile', [DashboardController::class, 'saveProfile'])->name('save-profile');

    Route::get('change-password', [DashboardController::class, 'change_password'])->name('change-password');
    Route::post('save-password', [DashboardController::class, 'save_password'])->name('save-password');

    $adminPrefix = "audittrails";
    Route::group(['prefix' => $adminPrefix, 'middleware' => ['admin']], function() {
        Route::get('audit-trails', [AuditTrailsController::class, 'list'])->name('audit-trails');
        Route::post('audit-trails-ajaxcall', [AuditTrailsController::class, 'ajaxcall'])->name('audit-trails-ajaxcall');
    });

    //  Bucket Route
    Route::get('bucket/list', [BucketController::class, 'list'])->name('bucket.list');
    Route::get('bucket/add', [BucketController::class, 'add'])->name('bucket.add');
    Route::post('bucket/save-add-bucket', [BucketController::class, 'saveAdd'])->name('bucket.save-add-bucket');
    Route::get('bucket/edit/{id}', [BucketController::class, 'edit'])->name('bucket.edit');
    Route::post('bucket/save-edit-bucket', [BucketController::class, 'saveEdit'])->name('bucket.save-edit-bucket');
    Route::post('bucket/ajaxcall', [BucketController::class, 'ajaxcall'])->name('bucket.ajaxcall');

    // Balls Route
    Route::get('ball/list', [BallController::class, 'list'])->name('ball.list');
    Route::get('ball/add', [BallController::class, 'add'])->name('ball.add');
    Route::post('ball/save-add-ball', [BallController::class, 'saveAdd'])->name('ball.save-add-ball');
    Route::get('ball/edit/{id}', [BallController::class, 'edit'])->name('ball.edit');
    Route::post('ball/save-edit-ball', [BallController::class, 'saveEdit'])->name('ball.save-edit-ball');
    Route::post('ball/ajaxcall', [BallController::class, 'ajaxcall'])->name('ball.ajaxcall');

    // Bucket-suggestions Route
    Route::get('bucket-suggestions/list', [BucketsuggestionsController::class, 'list'])->name('bucket-suggestions.list');
    Route::get('bucket-suggestions/add', [BucketsuggestionsController::class, 'add'])->name('bucket-suggestions.add');
    Route::post('bucket-suggestions/save-add-ball', [BucketsuggestionsController::class, 'saveAdd'])->name('bucket-suggestions.save-add-ball');
    Route::get('bucket-suggestions/edit/{id}', [BucketsuggestionsController::class, 'edit'])->name('bucket-suggestions.edit');
    Route::post('bucket-suggestions/save-edit-ball', [BucketsuggestionsController::class, 'saveEdit'])->name('bucket-suggestions.save-edit-ball');
    Route::post('bucket-suggestions/ajaxcall', [BucketsuggestionsController::class, 'ajaxcall'])->name('bucket-suggestions.ajaxcall');

});
