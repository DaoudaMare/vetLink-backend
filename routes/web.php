<?php

use App\Http\Controllers\Admin\Gamifications\GamificationController;
use App\Http\Controllers\Admin\Notifications\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ViewLoginController;
use App\Http\Controllers\Admin\ViewBadgeController;
use App\Http\Controllers\Admin\ViewDashboardController;
use App\Http\Controllers\Admin\Produits\ProduitController;
use App\Http\Controllers\Admin\Transactions\TransactionController;
use App\Http\Controllers\Admin\Utilisateurs\UtilisateursController;
use App\Http\Controllers\Auth\StoreLoginController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('vetlink')->group(function () {
    Route::get('login', ViewLoginController::class)->name('login');
    Route::post('authenticate', StoreLoginController::class)->name('authenticate');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/live-stats', [App\Http\Controllers\Admin\AdminDashboardController::class, 'liveStats'])->name('dashboard.live-stats');
    Route::get('dashboard/sales-chart', [App\Http\Controllers\Admin\AdminDashboardController::class, 'salesChart'])->name('dashboard.sales-chart');
    Route::get('dashboard/alerts', [App\Http\Controllers\Admin\AdminDashboardController::class, 'alerts'])->name('dashboard.alerts');

    // Gestion des utilisateurs
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminUserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/suspend', [App\Http\Controllers\Admin\AdminUserController::class, 'suspend'])->name('suspend');
        Route::post('/{user}/activate', [App\Http\Controllers\Admin\AdminUserController::class, 'activate'])->name('activate');
        Route::post('/{user}/verify-profile', [App\Http\Controllers\Admin\AdminUserController::class, 'verifyProfile'])->name('verify-profile');
        Route::post('/{user}/reset-password', [App\Http\Controllers\Admin\AdminUserController::class, 'resetPassword'])->name('reset-password');
    });

    // Gestion des produits
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('index');
        Route::get('/pending', [App\Http\Controllers\Admin\AdminProductController::class, 'pending'])->name('pending');
        Route::get('/top-selling', [App\Http\Controllers\Admin\AdminProductController::class, 'topSelling'])->name('top-selling');
        Route::get('/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}/approve', [App\Http\Controllers\Admin\AdminProductController::class, 'approve'])->name('approve');
        Route::post('/{product}/reject', [App\Http\Controllers\Admin\AdminProductController::class, 'reject'])->name('reject');
        Route::post('/{product}/feature', [App\Http\Controllers\Admin\AdminProductController::class, 'feature'])->name('feature');
        Route::post('/{product}/adjust-stock', [App\Http\Controllers\Admin\AdminProductController::class, 'adjustStock'])->name('adjust-stock');
        Route::delete('/{product}/images/{image}', [App\Http\Controllers\Admin\AdminProductController::class, 'deleteImage'])->name('delete-image');
    });

    // Gestion des commandes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('index');
        Route::get('/pending', [App\Http\Controllers\Admin\AdminOrderController::class, 'pending'])->name('pending');
        Route::get('/disputes', [App\Http\Controllers\Admin\AdminOrderController::class, 'disputes'])->name('disputes');
        Route::get('/statistics', [App\Http\Controllers\Admin\AdminOrderController::class, 'statistics'])->name('statistics');
        Route::get('/export', [App\Http\Controllers\Admin\AdminOrderController::class, 'export'])->name('export');
        Route::get('/{order}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/update-status', [App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/update-delivery', [App\Http\Controllers\Admin\AdminOrderController::class, 'updateDeliveryStatus'])->name('update-delivery');
        Route::post('/{order}/mark-paid', [App\Http\Controllers\Admin\AdminOrderController::class, 'markAsPaid'])->name('mark-paid');
        Route::post('/{order}/cancel', [App\Http\Controllers\Admin\AdminOrderController::class, 'cancel'])->name('cancel');
    });

    // Modération du chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminChatController::class, 'index'])->name('index');
        Route::get('/flagged', [App\Http\Controllers\Admin\AdminChatController::class, 'flaggedMessages'])->name('flagged');
        Route::get('/statistics', [App\Http\Controllers\Admin\AdminChatController::class, 'statistics'])->name('statistics');
        Route::get('/{conversation}', [App\Http\Controllers\Admin\AdminChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/flag', [App\Http\Controllers\Admin\AdminChatController::class, 'flag'])->name('flag');
        Route::post('/{conversation}/unflag', [App\Http\Controllers\Admin\AdminChatController::class, 'unflag'])->name('unflag');
        Route::post('/{conversation}/close', [App\Http\Controllers\Admin\AdminChatController::class, 'closeConversation'])->name('close');
        Route::post('/{conversation}/reopen', [App\Http\Controllers\Admin\AdminChatController::class, 'reopenConversation'])->name('reopen');
        Route::delete('/messages/{message}', [App\Http\Controllers\Admin\AdminChatController::class, 'deleteMessage'])->name('delete-message');
        Route::post('/users/{user}/suspend', [App\Http\Controllers\Admin\AdminChatController::class, 'suspendUser'])->name('suspend-user');
        Route::post('/users/{user}/unsuspend', [App\Http\Controllers\Admin\AdminChatController::class, 'unsuspendUser'])->name('unsuspend-user');
        Route::post('/users/{user}/warning', [App\Http\Controllers\Admin\AdminChatController::class, 'sendWarning'])->name('send-warning');
    });

});