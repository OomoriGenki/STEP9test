<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 💡 修正点: お問い合わせフォームは通常、非ログインユーザーにも許可されるため true に変更
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // 💡 1. rules() メソッドの内容を画像に合わせて置き換え
        return [
            'name' => 'required|string|max:255', //
            'email' => 'required|email|max:255', //
            'message' => 'required|string', //
        ];
    }
    
    /**
     * 💡 2. messages() メソッドを新たに追加
     * バリデーションエラーメッセージを定義します。
     */
    public function messages(): array
    {
        // 各バリデーションルールに対するエラーメッセージ
        return [
            'name.required' => '名前は必須です。',
            'name.max' => '名前は255文字以内で入力してください。',
            'email.required' => 'Eメールは必須です。',
            'email.max' => 'Eメールは255文字以内で入力してください。',
            'message.required' => '内容は必須です。',
        ];
    }
}