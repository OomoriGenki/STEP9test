<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController; // 💡 ProductControllerをItemControllerに統一
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LikeController; 

// --- 認証関連 (ログイン/新規登録) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 商品関連 (非ログインでもアクセス可能) ---

// 💡 修正点: ItemControllerを使用し、ルート名をitems.indexに統一
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 💡 修正点: ItemControllerを使用し、ルートパラメータ名を{item}に統一
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');


// --- お問い合わせ関連 ---
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');


// --- 認証済みユーザーのみアクセス可能 ---
Route::middleware(['auth'])->group(function () {
    
    // --- マイページ・アカウント関連 ---
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/account/edit', [MypageController::class, 'editAccount'])->name('account.edit');
    Route::post('/account/edit', [MypageController::class, 'updateAccount'])->name('account.update');

    // --- 出品関連 ---
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // 💡 修正点: ルートパラメータ名を{item}に統一
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    // 💡 修正点: 更新処理はPATCHメソッドを使用
    Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // --- 購入関連 (PurchaseControllerを使用) ---
    // PurchaseControllerのcreate/storeメソッドを使うのが自然
    Route::get('/items/{item}/purchase', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/items/{item}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');
    
    // --- いいね機能 (LikeControllerを使用) ---
    // 💡 修正点: ルートパラメータ名を{item}に統一
    Route::post('/items/{item}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/items/{item}/unlike', [LikeController::class, 'destroy'])->name('likes.destroy');
});