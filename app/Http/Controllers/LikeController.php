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
     * ① いいねを追加する処理 (POST /items/{item}/like)
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
            // 既に存在する場合は、特に何もしないで元のページに戻る
            return back()->with('error', '既にこの商品にいいねしています。');
        }

        // 3. いいねをデータベースに追加
        Like::create([
            'user_id' => $userId,
            'item_id' => $itemId,
        ]);

        // 4. 成功後に元のページにリダイレクト
        return back()->with('success', 'いいねを追加しました。');
    }

    /**
     * ② いいねを削除する処理 (DELETE /items/{item}/unlike)
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
            // いいねが存在しない場合は、特に何もしないで元のページに戻る
            return back()->with('error', '削除するいいねが見つかりませんでした。');
        }

        // 2. 削除を実行
        $like->delete();

        // 3. 成功後に元のページにリダイレクト
        return back()->with('success', 'いいねを削除しました。');
    }
}