<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase; // ★ Purchaseモデルを追加

class UserController extends Controller
{
    /**
     * 認証済みユーザーのマイページを表示します。
     */
    public function index()
    {
        // 1. 認証済みユーザーを取得
        $user = Auth::user();

        // 2. ユーザーが出品した商品を取得 (itemsリレーションを使用)
        $items = $user->items()->orderBy('id', 'asc')->get();

        // 3. ユーザーがいいねした商品を取得 (likesリレーションを通じてItemを取得)
        $likedItems = $user->likes()
                           ->with('item')
                           ->get()
                           ->pluck('item');
                           
        // 4. ユーザーが購入した取引を取得 (Purchaseリレーションを使用) ★ 追加
        // 購入時の情報（価格、ステータス）が必要なため、Purchaseモデル自体を渡します。
        $purchasedItems = $user->purchases()
                                ->with('item') 
                                ->orderBy('created_at', 'asc') // ★ 昇順に変更
                                ->get();


        // ★ purchasedItems を追加してビューに渡す
        return view('mypage.index', compact('user', 'items', 'likedItems', 'purchasedItems'));
    }

    /**
     * プロフィール編集画面を表示します。
     */
    public function editAccount() // ★ メソッド名をeditAccountに変更
    {
        // ユーザー情報を取得
        $user = Auth::user();
        
        // プロフィール編集ビューを返す
        return view('mypage.account_edit', compact('user')); 
    }

    /**
     * プロフィール情報を更新します。
     */
    public function updateAccount(Request $request) // ★ メソッド名をupdateAccountに変更
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(), // 自分のメールアドレスは除外
            // 必要に応じてプロファイル画像や住所などのカラムを追加
        ]);
        
        // 2. ユーザー情報の更新
        Auth::user()->update($validated);
        
        // 3. リダイレクト
        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました。');
    }
}