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
        // 1. 数量のバリデーションと取得
        $validated = $request->validate([
            // quantityは必須、整数、1以上、かつ商品の在庫数以下であることを確認
            'quantity' => 'required|integer|min:1|max:' . $item->stock, 
        ]);
        $quantity = $validated['quantity'];

        // 自分の出品物でないか再チェック（不正アクセス防止）
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'ご自身が出品した商品は購入できません。');
        }

        $user = Auth::user();

        try {
            // トランザクション処理: 在庫減少と購入記録を同時に実行
            // $quantity を use で渡し、トランザクション内で使用可能にする
            DB::transaction(function () use ($item, $user, $quantity) {
                
                // 2. 在庫チェックと減少をアトミックに実行
                $updated = $item->where('id', $item->id)
                                 // $quantity以上の在庫があるかチェック
                                ->where('stock', '>=', $quantity)
                                 // $quantity 分だけ在庫を減らす
                                ->decrement('stock', $quantity); 
                
                if (!$updated) {
                    // 在庫更新が失敗した場合
                    throw new \Exception('在庫の確保に失敗しました。購入希望数（' . $quantity . '）に対して在庫が不足しているか、既に売り切れています。');
                }

                // 3. Purchase テーブルに購入記録を保存
                Purchase::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'price' => $item->price * $quantity,     // 合計金額を記録 (単価 x 数量)
                    'quantity' => $quantity,                 // 数量を記録
                    'status' => 'completed',                 // 初期ステータス (例: 完了)
                ]);
            });

            // 4. リダイレクト先をマイページ（購入履歴を確認できるページ）に変更
            return redirect()->route('mypage.index')->with('success', $item->name . 'を' . $quantity . '点購入しました！');

        } catch (\Exception $e) {
            // エラー時は購入手続き画面に戻す
            return redirect()->route('purchases.create', $item)->with('error', '購入処理中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}