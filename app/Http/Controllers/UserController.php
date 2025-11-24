<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Profile;

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
        $likedItems = $user->likedItems()->orderBy('id', 'asc')->get();
                           
        // 4. ユーザーが購入した取引を取得 (Purchaseリレーションを使用) ★ 追加
        // 購入時の情報（価格、ステータス）が必要なため、Purchaseモデル自体を渡します。
        $purchases = $user->purchases()
                                 ->with('item') 
                                 ->orderBy('created_at', 'asc')
                                 ->get();


        return view('mypage.index', compact('user', 'items', 'likedItems', 'purchases'));
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
    public function updateAccount(Request $request) 
    {
        // ★ 修正: $user を定義 (トランザクションクロージャ内で使用するため)
        $user = Auth::user();

        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . Auth::id(), // 自分のメールアドレスは除外
            'full_name' => ['nullable', 'string', 'max:255'],
            'full_name_kana' => ['nullable', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'], 
        ]);
        
        // トランザクション開始
        try {
            DB::transaction(function () use ($user, $validated) {
                
                // ユーザーに紐づくプロフィールを取得または新規作成
                // ※ Userモデルに 'profile' リレーションが定義されている前提
                $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
                
                // データを更新
                $profile->full_name = $validated['full_name'];
                $profile->full_name_kana = $validated['full_name_kana'];
                
                $profile->save();
            });

            return redirect()->route('mypage.editAccount')->with('success', 'アカウント情報を更新しました。');

        } catch (\Exception $e) {
            // エラー処理
            // return redirect()->back()->with('error', '情報の更新に失敗しました。');
            // デバッグのため一時的にエラーを再スロー
            throw $e; 
        }
    }
}