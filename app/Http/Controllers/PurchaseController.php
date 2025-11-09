<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\StockUnavailableException; // カスタム例外（必要に応じて定義）

class PurchaseController extends Controller
{
    /**
     * 購入手続き画面を表示
     */
    public function create(Item $item)
    {
        // 1. 在庫チェック
        if ($item->stock <= 0) {
            return back()->with('error', 'この商品は現在在庫切れです。');
        }

        // 2. 自分の出品物でないかチェック（自分で自分の商品を買えないようにする）
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'ご自身が出品した商品は購入できません。');
        }
        
        // 3. 既に購入済みでないかチェック（必要に応じて）
        // $isPurchased = Purchase::where('item_id', $item->id)->where('status', '!=', 'cancelled')->exists();
        // if ($isPurchased && $item->stock < 1) { ... }

        return view('purchases.create', compact('item'));
    }

    /**
     * 購入処理を実行 (DB記録と在庫減少)
     */
    public function store(Request $request, Item $item)
    {
        // 自分の出品物でないか再チェック（不正アクセス防止）
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'ご自身が出品した商品は購入できません。');
        }

        $user = Auth::user();

        try {
            // トランザクション処理: 在庫減少と購入記録を同時に実行
            DB::transaction(function () use ($item, $user) {
                
                // 1. 在庫チェックと減少をアトミックに実行
                // stock > 0 であることを確認しながら、同時に在庫数を1減らす (競合対策)
                $updated = $item->where('id', $item->id)
                                ->where('stock', '>', 0)
                                ->decrement('stock');
                
                if (!$updated) {
                    // 在庫更新が失敗した場合 (在庫が0以下だった場合)
                    throw new \Exception('在庫の確保に失敗しました。この商品は既に売り切れた可能性があります。');
                }

                // 2. Purchase テーブルに購入記録を保存
                Purchase::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'price' => $item->price,       // 購入時の価格を記録 (重要)
                    'quantity' => 1,               // 数量
                    'status' => 'pending_payment', // 初期ステータス (例: 支払い待ち)
                ]);
            });

            return redirect()->route('items.show', $item)->with('success', '購入が完了しました！');

        } catch (\Exception $e) {
            // トランザクション内で発生した例外をキャッチし、ユーザーにメッセージを返す
            return redirect()->route('items.show', $item)->with('error', '購入処理中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}