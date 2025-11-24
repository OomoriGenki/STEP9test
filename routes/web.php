<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController; 

// --- 認証関連 (ログイン/新規登録/ログアウト) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 認証済みユーザーのみアクセス可能
Route::middleware(['auth'])->group(function () {
    
    // --- マイページ・アカウント関連 (UserController) ---
    // マイページ表示 (出品商品、いいね一覧など)
    Route::get('/mypage', [UserController::class, 'index'])->name('mypage.index');
    
    // プロフィール編集フォーム - ルート名を'mypage.editAccount'に統一
    Route::get('/account/edit', [UserController::class, 'editAccount'])->name('mypage.editAccount'); 
    
    // プロフィール情報更新 - ルート名を'mypage.updateAccount'に統一
    Route::post('/account/edit', [UserController::class, 'updateAccount'])->name('mypage.updateAccount');

    // --- 出品関連 (ItemController) ---
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // --- 購入関連 (PurchaseController) ---
    Route::get('/purchases/{item}', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases/{item}', [PurchaseController::class, 'store'])->name('purchases.store');
    
    // --- いいね機能 (LikeController) ---
    Route::post('/likes/{item}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{item}', [LikeController::class, 'destroy'])->name('likes.destroy');
});

// 一般ユーザー向け (非ログインでもアクセス可能)

// 商品一覧 (トップページ)
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show'); // ここに配置

// お問い合わせ関連
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');