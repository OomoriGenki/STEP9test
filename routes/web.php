<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// 商品一覧、商品詳細などを担当するコントローラー（規約通りアッパーキャメル＋Controller）
use App\Http\Controllers\ProductController;
// 出品・編集などを担当するコントローラー
use App\Http\Controllers\ItemController;
// マイページ・アカウント編集などを担当するコントローラー
use App\Http\Controllers\MypageController;
// お問い合わせを担当するコントローラー
use App\Http\Controllers\ContactController;
// LikeControllerの利用を宣言
use App\Http\Controllers\LikeController; 

// --- 認証関連 (ログイン/新規登録) ---

// ログイン画面表示 (GET /login) -> 画面遷移図: ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// ログイン処理実行 (POST /login)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 新規ユーザー登録画面表示 (GET /register) -> 画面遷移図: 新規ユーザー登録
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// 新規ユーザー登録処理実行 (POST /register)
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ログアウト処理 (POST /logout)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 商品関連 (非ログインでもアクセス可能) ---

// 商品一覧表示 (GET /) -> 画面遷移図: 商品一覧, Homeリンク
Route::get('/', [ProductController::class, 'index'])->name('products.index');
// 商品詳細表示 (GET /products/{id}) -> 画面遷移図: 商品詳細
// {id}は商品のIDを表すパラメーター
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


// --- 認証済みユーザーのみアクセス可能 ---
Route::middleware(['auth'])->group(function () {
    
    // --- マイページ・アカウント関連 ---

    // マイページ表示 (GET /mypage) -> 画面遷移図: マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    
    // アカウント編集画面表示 (GET /account/edit) -> 画面遷移図: アカウント編集
    Route::get('/account/edit', [MypageController::class, 'editAccount'])->name('account.edit');
    // アカウント情報更新処理 (POST /account/edit)
    Route::post('/account/edit', [MypageController::class, 'updateAccount'])->name('account.update');

    // --- 出品関連 (ItemControllerを使用) ---
    
    // 商品新規登録画面表示 (GET /items/create) -> 画面遷移図: 商品新規登録
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    // 商品新規登録処理実行 (POST /items)
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // 出品商品詳細表示 (GET /items/{id}) -> 画面遷移図: 出品商品詳細
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    
    // 商品編集画面表示 (GET /items/{id}/edit) -> 画面遷移図: 商品編集
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    // 商品編集処理実行 (POST /items/{id})
    Route::post('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    // 商品削除処理 (DELETE /items/{id})
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // --- 購入関連 (PurchaseControllerを使用) ---
    
    // 購入画面表示/処理 (APIパターンで実装とあるため、ここではフロントエンド部分のみを定義)
    Route::get('/purchase', function () {
        // 購入画面のビューを返す
        return view('purchase.create');
    })->name('purchase.create');

});

// --- お問い合わせ関連 ---

// お問い合わせフォーム表示 (GET /contact) -> 画面遷移図: お問い合わせフォーム
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
// お問い合わせ送信処理 (POST /contact)
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// --- 非同期処理（いいね機能）のルーティング ---

// ① いいね追加 (POST)
// URL: /items/{item_id}/like
// LikeControllerのstoreメソッドを使用
Route::post('/items/{item_id}/like', [LikeController::class, 'store'])->middleware('auth');

// ② いいね削除 (DELETE)
// URL: /items/{item_id}/like
// LikeControllerのdestroyメソッドを使用
Route::delete('/items/{item_id}/like', [LikeController::class, 'destroy'])->middleware('auth');