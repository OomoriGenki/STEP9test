<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item; // Itemモデルを使用
use Illuminate\Http\Request; // Requestはstoreで使用するが、引数から削除
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * 認証済みユーザーのみアクセス可能であることを保証
     */
    public function __construct()
    {
        // ルーティングでミドルウェアを設定済みの場合、この記述は省略可能
        $this->middleware('auth');
    }

    /**
     * ① いいねを追加する処理 (POST /likes/{item})
     * ルートモデルバインディングによりItemモデルのインスタンスを受け取る
     */
    public function store(Item $item)
    {
        $itemId = $item->id;
        $userId = Auth::id();

        // 既に「いいね」が存在するかチェック (二重登録防止)
        $like = Like::where('user_id', $userId)
                         ->where('item_id', $itemId)
                         ->first();

        if ($like) {
            // 既に存在する場合は、元のページに戻る
            return back()->with('error', '既にこの商品にいいねしています。');
        }

        // いいねをデータベースに追加
        Like::create([
            'user_id' => $userId,
            'item_id' => $itemId,
        ]);

        // 成功後に元のページにリダイレクト
        return back()->with('success', 'いいねを追加しました。');
    }

    /**
     * ② いいねを削除する処理 (DELETE /likes/{item})
     * ルートモデルバインディングによりItemモデルのインスタンスを受け取る
     */
    public function destroy(Item $item)
    {
        $itemId = $item->id;
        $userId = Auth::id();

        // 削除対象のLikeレコードを検索
        // ユーザーとアイテムIDが一致するレコードのみを削除
        $deletedCount = Like::where('user_id', $userId)
                         ->where('item_id', $itemId)
                         ->delete(); // first()で取得せず、直接delete()を実行する方が効率的

        if ($deletedCount === 0) {
            // 削除されたレコードがない場合
            return back()->with('error', '削除するいいねが見つかりませんでした。');
        }

        // 成功後に元のページにリダイレクト
        return back()->with('success', 'いいねを削除しました。');
    }
}