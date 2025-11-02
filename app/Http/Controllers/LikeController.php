<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * 認証済みユーザーのみアクセス可能であることを保証
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ① いいねを追加する処理 (POST /api/likes)
     * Requestからitem_idを受け取り、Likeレコードを作成する
     */
    public function store(Request $request)
    {
        // 1. バリデーション: item_idが必須で、itemsテーブルに存在するIDであること
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $itemId = $request->item_id;
        $userId = Auth::id();

        // 2. 既に「いいね」が存在するかチェック (二重登録防止)
        $like = Like::where('user_id', $userId)
                    ->where('item_id', $itemId)
                    ->first();

        if ($like) {
            // 既に存在する場合はエラー、または成功として返す（ idempotency: 冪等性）
            return response()->json(['message' => '既にいいねされています。'], 200);
        }

        // 3. いいねをデータベースに追加
        Like::create([
            'user_id' => $userId,
            'item_id' => $itemId,
        ]);

        // 4. 成功レスポンスを返す (ステータス: 201 Created)
        // いいね総数など、最新の情報を返すのが一般的
        $item = Item::find($itemId);
        return response()->json([
            'message' => 'いいねを追加しました。',
            'is_liked' => true,
            'likes_count' => $item->likes()->count()
        ], 201);
    }

    /**
     * ② いいねを削除する処理 (DELETE /api/likes/{item_id})
     * URLパラメータからitem_idを受け取り、Likeレコードを削除する
     */
    public function destroy($itemId)
    {
        $userId = Auth::id();

        // 1. 削除対象のLikeレコードを検索
        $like = Like::where('user_id', $userId)
                    ->where('item_id', $itemId)
                    ->first();

        if (!$like) {
            // いいねが存在しない場合はエラー、または成功として返す
            return response()->json(['message' => 'いいねが見つかりませんでした。'], 404);
        }

        // 2. 削除を実行
        $like->delete();

        // 3. 成功レスポンスを返す (ステータス: 200 OK)
        $item = Item::find($itemId);
        return response()->json([
            'message' => 'いいねを削除しました。',
            'is_liked' => false,
            'likes_count' => $item->likes()->count()
        ], 200);
    }
}