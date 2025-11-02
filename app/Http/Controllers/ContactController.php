<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // 💡 Mailファサードを使用
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ContactRequest; // 💡 作成したフォームリクエストを使用
use App\Mail\ContactMail; // 💡 後で作成するメールクラスを使用

class ContactController extends Controller
{
    /**
     * 💡 お問い合わせフォームを表示
     */
    public function showForm()
    {
        // お問い合わせフォームを表示
        return view('contact'); // 💡 'contact.blade.php' ビューを返す
    }
    
    // 💡 お問い合わせフォームの内容を送信
    public function submitForm(ContactRequest $request) // 💡 ContactRequestでバリデーション
    {
        // バリデーションされたメール送信の詳細を設定
        $data = $request->validated(); // 💡 バリデーション済みのデータを取得
        
        try {
            // 管理者にメールを送信
            // env('ADMIN_EMAIL') は .env ファイルに定義されている必要あり
            Mail::to(env('ADMIN_EMAIL'))->send(new ContactMail($data)); // 💡 ContactMailクラスを使ってメールを送信
            
        } catch (\Exception $e) {
            // 送信失敗時のエラーハンドリング
            Log::error('メール送信エラー: ' . $e->getMessage()); // ログに出力
            return back()->with('error', 'メール送信に失敗しました。後でもう一度お試しください。'); //
        }
        
        // 一覧画面にリダイレクトし、成功メッセージを表示する
        return redirect()->route('index')
            ->with('success', 'お問い合わせが送信されました！'); //
    }
}
