<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController; 

// --- 認証関連 (ログイン/新規登録/ログアウト) ---
// 認証機能はLaravel Breeze/Jetstreamなどで自動生成されることが多いですが、ここでは手動で定義します。
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 1. 一般ユーザー向け (非ログインでもアクセス可能) ---

// 商品一覧 (トップページ)
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 商品詳細
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');


// お問い合わせ関連
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');


// --- 2. 認証済みユーザーのみアクセス可能 ---
Route::middleware(['auth'])->group(function () {
    
    // --- マイページ・アカウント関連 (UserController) ---
    // マイページ表示 (出品商品、いいね一覧など)
    Route::get('/mypage', [UserController::class, 'index'])->name('mypage.index');
    // プロフィール編集フォーム
    Route::get('/account/edit', [UserController::class, 'editAccount'])->name('account.edit');
    // プロフィール情報更新
    Route::post('/account/edit', [UserController::class, 'updateAccount'])->name('account.update');

    // --- 出品関連 (ItemController) ---
    // 出品フォームの表示
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    // 商品の新規登録
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    // 商品編集フォームの表示
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    // 商品の更新
    Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    // 商品の削除
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // --- 購入関連 (PurchaseController - RESTfulに統一) ---
    // 購入手続き画面の表示 (購入リソースを作成するためのフォーム)
    Route::get('/purchases/{item}', [PurchaseController::class, 'create'])->name('purchases.create');
    // 購入の実行 (購入リソースの登録)
    Route::post('/purchases/{item}', [PurchaseController::class, 'store'])->name('purchases.store');
    
    // --- いいね機能 (LikeController - RESTfulに統一) ---
    // いいねの登録 (Likesリソースの作成)
    // {item}はどの商品に対するいいねかを指定
    Route::post('/likes/{item}', [LikeController::class, 'store'])->name('likes.store');
    // いいねの削除 (Likesリソースの削除)
    Route::delete('/likes/{item}', [LikeController::class, 'destroy'])->name('likes.destroy');
});