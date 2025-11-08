<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 購入手続き画面を表示
     */
    public function create(Item $item)
    {
        // 在庫チェック
        if ($item->stock <= 0) {
            return back()->with('error', 'この商品は現在在庫切れです。');
        }
        
        return view('purchases.create', compact('item'));
    }

    /**
     * 購入処理を実行 (DB記録と在庫減少)
     */
    public function store(Request $request, Item $item)
    {
        // ログインユーザー
        $user = Auth::user();

        // トランザクション処理: 在庫減少と購入記録を同時に実行
        DB::transaction(function () use ($item, $user) {
            
            // 1. 在庫チェックと減少
            if ($item->stock <= 0) {
                // トランザクション内で例外を投げるとロールバックされる
                throw new \Exception('在庫がありません。');
            }
            $item->decrement('stock');

            // 2. Purchase テーブルに購入記録を保存
            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        });

        return redirect()->route('items.show', $item)->with('success', '購入が完了しました！');

    }
}