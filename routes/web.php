<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RawMaterialController as AdminRawMaterialController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

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

Auth::routes(['verify' => true]);
Route::get('/', [PageController::class, 'dashboard'])->name('dashboard')->middleware('public');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show')->middleware('public');
Route::get('/terms-condition', [PageController::class, 'termsCondition'])->name('terms.condition')->middleware('public');

Route::get('/admin', [LoginController::class, 'adminLoginForm'])->name('admin.login.form');
Route::post('/admin', [LoginController::class, 'loginAdmin'])->name('admin.login');

// Protected routes (require auth and verified email)\
Route::group(['middleware' => ['auth', 'verified']], function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

	Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
	Route::post('/notifications', [ProfileController::class, 'readNotifications'])->name('notifications.read');

	// User Route
	Route::group(['middleware' => ['user']], function () {
		Route::name('user.')->group(function () {
			Route::middleware('profile.complete')->group(function () {
				Route::group(['prefix' => 'products'], function () {
					Route::get('/', [ProductController::class, 'index'])->name('products.list');
					Route::get('/checkout/{id}', [ProductController::class, 'checkout'])->name('product.checkout');
					Route::post('/checkout/{id}', [ProductController::class, 'checkoutStore'])->name('product.checkout.store');
				});

				Route::get('/payment-options', [PageController::class, 'paymentOptions'])->name('payment.options');

				Route::group(['prefix' => 'orders'], function () {
					Route::get('/', [OrderController::class, 'index'])->name('orders.list');
					Route::get('/{id}', [OrderController::class, 'show'])->name('order.show')->where('id', '[0-9]+');

					Route::post('/{id}/upload-payment', [OrderController::class, 'uploadPayment'])->name('order.upload.payment');
					Route::post('/{id}/feedback', [OrderController::class, 'feedback'])->name('order.feedback');
					Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
					Route::post('/{id}/receive', [OrderController::class, 'receive'])->name('order.receive');
				});
			});
		});
	});

	// Admin Route
	Route::group(['prefix' => 'admin', 'middleware' => ['admin_staff', 'profile.complete']], function () {
		Route::name('admin.')->group(function () {
			Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard');

			Route::group(['prefix' => 'products'], function () {
				Route::get('/', [AdminProductController::class, 'index'])->name('products.list');
				Route::post('/', [AdminProductController::class, 'store'])->name('product.store');
				Route::get('/archived', [AdminProductController::class, 'archived'])->name('products.archived');
				Route::get('/{id}', [AdminProductController::class, 'edit'])->name('product.edit')->where('id', '[0-9]+');
				Route::delete('/{id}', [AdminProductController::class, 'delete'])->name('product.delete');
				Route::patch('/{id}', [AdminProductController::class, 'update'])->name('product.update');
				Route::patch('/{id}/restore', [AdminProductController::class, 'restore'])->name('product.restore');
				Route::get('/{id}/feedbacks', [AdminProductController::class, 'feedbacks'])->name('product.feedbacks');
				Route::delete('/{id}/feedbacks/{feedback_id}', [AdminProductController::class, 'deleteFeedback'])->name('product.feedback.delete');
				Route::post('/{id}/restock', [AdminProductController::class, 'restock'])->name('product.restock');
				Route::post('/{id}/restock/{batch_id}', [AdminProductController::class, 'restockUpdate'])->name('product.restock.update');
				Route::delete('/{id}/restock/{batch_id}', [AdminProductController::class, 'restockDelete'])->name('product.restock.delete');

				Route::group(['prefix' => 'raw-materials'], function () {
					Route::get('/', [AdminRawMaterialController::class, 'index'])->name('materials.list');
					Route::post('/', [AdminRawMaterialController::class, 'store'])->name('material.store');
					Route::get('/{id}', [AdminRawMaterialController::class, 'edit'])->name('material.edit');
					Route::delete('/{id}', [AdminRawMaterialController::class, 'delete'])->name('material.delete');
					Route::patch('/{id}', [AdminRawMaterialController::class, 'update'])->name('material.update');
					Route::post('/{id}/restock', [AdminRawMaterialController::class, 'restock'])->name('material.restock');
					Route::post('/{id}/restock/{batch_id}', [AdminRawMaterialController::class, 'restockUpdate'])->name('material.restock.update');
					Route::delete('/{id}/restock/{batch_id}', [AdminRawMaterialController::class, 'restockDelete'])->name('material.restock.delete');
				});
			});

			Route::group(['prefix' => 'orders'], function () {
				Route::get('/{status?}', [AdminOrderController::class, 'index'])->name('orders.list')->where('status', '[A-Za-z\W]+');
				Route::get('/{id}', [AdminOrderController::class, 'show'])->name('order.show')->where('id', '[0-9]+');

				Route::post('/{status}/{id}', [AdminOrderController::class, 'changeStatus'])->name('order.change.status');
			});

			Route::group(['middleware' => ['admin']], function () {
				Route::group(['prefix' => 'reports'], function () {
					Route::get('/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
					Route::get('/sales/export', [AdminReportController::class, 'salesExport'])->name('reports.sales.export');
					Route::get('/inventory', [AdminReportController::class, 'productInventory'])->name('reports.product.inventory');
					Route::get('/material-inventory', [AdminReportController::class, 'materialInventory'])->name('reports.material.inventory');
					Route::get('/inventory/export', [AdminReportController::class, 'inventoryExport'])->name('reports.inventory.export');
				});
	
				Route::group(['prefix' => 'users'], function () {
					Route::get('/', [AdminUserController::class, 'index'])->name('users.list');
					Route::get('/admin', [AdminUserController::class, 'admin'])->name('users.admin');
					Route::post('/admin', [AdminUserController::class, 'createAdmin'])->name('users.create.admin');
					Route::patch('/disable/{id}', [AdminUserController::class, 'disable'])->name('user.disable');
					Route::patch('/enable/{id}', [AdminUserController::class, 'enable'])->name('user.enable');
				});

				Route::group(['prefix' => 'settings'], function () {
					Route::get('/', [AdminSettingController::class, 'index'])->name('settings.index');
					Route::post('/payments', [AdminSettingController::class, 'savePayments'])->name('settings.save.payments');
				});
	
				Route::get('/icons', [PageController::class, 'icons'])->name('page.icons');
			});
		});
	});
});