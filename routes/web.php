<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\WelcomeController;
use App\Models\Like;

// Главная страница
Route::get('/', [WelcomeController::class, 'index'])->name('/home');

// Страница доставки и оплаты
Route::get('/deliver-and-pay', function () {
    return view('deliver-and-payment');
})->name('/deliver-and-payment');

// Страница магазина
Route::get('/shop', [ShopController::class, 'index'])->name('/shop');
Route::get('/shop/category/{id}', [ShopController::class, 'show_category'])->name('/shop/category/{id}');

// Страница товара в магазине
Route::get('/shop/show/{id}', [ShopController::class, 'show'])->name('/shop/show/{id}');

// Личный кабинет (требует авторизации)
Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('auth');

// Страница ваших заказов (требует авторизации)
Route::get('/dashboard-your-orders', [DashboardUserController::class, 'your_orders'])->name('/dashboard-your-orders')->middleware('auth');

// Страница настроек вашей учетной записи (требует авторизации)
Route::get('/dashboard-settings', [DashboardUserController::class, 'dashboard_settings'])->name('/dashboard-settings')->middleware('auth');

// Страница выхода из учетной записи (требует авторизации)
Route::get('/dashboard-logout', [DashboardUserController::class, 'logout'])->name('/dashboard-logout')->middleware('auth');

// Обработчик изменения пароля (требует авторизации)
Route::post('/dashboard-password-update', [DashboardUserController::class, 'password_update'])->name('dashboard.password.update')->middleware('auth');

Route::post('/dashboard-upload-certificate', [DashboardUserController::class, 'upload_certificate'])->name('upload-certificate')->middleware('auth');
Route::post('/dashboard-remove-certificate', [DashboardUserController::class, 'remove_certificate'])->name('remove-certificate')->middleware('auth');

// Страница корзины (требует авторизации)
Route::get('/cart', [CartController::class, 'index'])->name('/cart')->middleware('auth');

// Добавление товара в корзину
Route::post('/cart-add/{id}', [CartController::class, 'add'])->name('/cart-add');

// Увеличение количества товара в корзине
Route::post('/cart-increment/{id}', [CartController::class, 'increment'])->name('/cart-increment')->middleware('auth');

// Уменьшение количества товара в корзине
Route::post('/cart-decrement/{id}', [CartController::class, 'decrement'])->name('/cart-decrement')->middleware('auth');

// Удаление товара из корзины
Route::get('/cart-delete/{id}', [CartController::class, 'delete'])->name('/cart-delete')->middleware('auth');

// Страница оформления заказа
Route::get('/checkout', [CartController::class, 'checkout'])->name('/checkout')->middleware('auth');

// Обработчик оформления заказа
Route::post('/checkout/store', [CartController::class, 'makeorder'])->name('/checkout/store')->middleware('auth');

// Страница "Ваши лайки" (требует авторизации)
Route::get('/your-likes', [LikesController::class, 'index'])->name('/your-likes')->middleware('auth');

// Обработчик добавления товара в избранное
Route::post('/shop/like/{id_product}/{id_user}', [LikesController::class, 'like'])->name('/shop/like/{id_product}/{id_user}')->middleware('auth');

// Обработчик удаления товара из избранного
Route::post('/shop/dislike/{id_product}/{id_user}', [LikesController::class, 'dislike'])->name('/shop/dislike/{id_product}/{id_user}')->middleware('auth');

// Экспорт ордера в CSV
Route::get('orders/export/{id}', [\App\Http\Controllers\OrderExportController::class, 'export'])->name('export-order/{id}')->middleware('auth');

// Вывод чека по ордеру
Route::get('/check/{id}', [\App\Http\Controllers\OrderPrintController::class, 'print'])->name('/check/{id}')->middleware('auth');
Route::get('/check/{id}', [\App\Http\Controllers\OrderPrintController::class, 'print'])->name('/check/{id}')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::get('/migrate', function(){
    \Artisan::call('storage:link');
    dd('migrated!');
});
