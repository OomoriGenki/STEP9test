<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index()
    {
        // ページネーション付きで商品を取得
        $items = Item::with(['user', 'likes'])->paginate(15); 
        
        // ログインユーザーIDを取得（いいね判定に使う）
        $userId = Auth::id();

        return view('items.index', compact('items', 'userId'));
    }

    /**
     * 商品詳細を表示
     */
    public function show(Item $item)
    {
        // Itemと関連情報（出品者、いいね情報）をロード
        $item->load(['user', 'likes']);
        
        // ログインユーザーIDを取得
        $userId = Auth::id();
        
        // ログインユーザーがいいねしているか判定
        $isLiked = $item->isLikedByUser($userId);

        return view('items.show', compact('item', 'isLiked'));
    }
}