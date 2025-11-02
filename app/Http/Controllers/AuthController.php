<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// コントローラー名は規約通りアッパーキャメル＋Controller
class AuthController extends Controller
{
    /**
     * ログインフォームを表示 (GET /login)
     * メソッド名は規約通りローワーキャメル
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * ログイン処理を実行 (POST /login)
     */
    public function login(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. 認証情報の準備
        $credentials = $request->only('email', 'password');

        // 3. 認証の実行
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // 認証成功
            $request->session()->regenerate();

            // ログイン成功時の遷移先 (商品一覧画面を想定)
            // ルーティング名は 'products.index' を想定
            return redirect()->intended(route('products.index'));
        }

        // 認証失敗
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Laravel標準のエラーメッセージ
        ]);
    }

    /**
     * 新規ユーザー登録フォームを表示 (GET /register)
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * 新規ユーザー登録処理を実行 (POST /register)
     */
    public function register(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name_kanji' => ['required', 'string', 'max:50'],
            'first_name_kanji' => ['required', 'string', 'max:50'],
            // カナ入力チェック (全角カタカナのみを想定)
            'last_name_kana' => ['required', 'string', 'max:50', 'regex:/^[ァ-ヶー]+$/u'],
            'first_name_kana' => ['required', 'string', 'max:50', 'regex:/^[ァ-ヶー]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // password_confirmationフィールドとの一致を確認する 'confirmed' ルール
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // 2. ユーザーの作成
        $user = User::create([
            'name' => $request->name,
            'last_name_kanji' => $request->last_name_kanji,
            'first_name_kanji' => $request->first_name_kanji,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'email' => $request->email,
            'password' => Hash::make($request->password), // パスワードは必ずハッシュ化
        ]);

        // 3. 登録後にユーザーをログイン状態にする
        Auth::login($user);

        // 4. 商品一覧へリダイレクト
        return redirect()->route('products.index');
    }

    /**
     * ログアウト処理を実行
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); // セッションを無効化
        $request->session()->regenerateToken(); // CSRFトークンを再生成

        // ログアウト後の遷移先 (ログイン画面またはトップページ)
        return redirect()->route('login');
    }
}