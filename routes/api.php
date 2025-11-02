<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController; // 追加

// 認証が必要なAPIルート
Route::middleware('auth:sanctum')->group(function () {
    // いいねの追加 (POST /api/likes)
    Route::post('/likes', [LikeController::class, 'store']);
    
    // いいねの削除 (DELETE /api/likes/{item_id})
    Route::delete('/likes/{item_id}', [LikeController::class, 'destroy']);
    
    // 現在認証されているユーザー情報を返す（開発用）
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});