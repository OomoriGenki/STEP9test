<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * お問い合わせフォームの表示
     */
    public function create()
    {
        // ビューファイルを返す
        return view('contact.create');
    }

    /**
     * フォーム送信データの処理
     */
    public function store(Request $request)
    {
        // ★ バリデーション処理などをここに追加 ★
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        // 例: メール送信処理（ここでは省略）
        // \Mail::to('admin@example.com')->send(new \App\Mail\ContactMail($request->all()));

        // 完了ページまたはメッセージを返す
        return redirect()->route('contact.create')->with('success', 'お問い合わせ内容を送信しました。');
    }
}