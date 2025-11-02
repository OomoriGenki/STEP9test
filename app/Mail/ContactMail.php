<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels; //

    // 💡 フォームデータ（名前、メール、内容）を保持するプロパティ
    public $details; 
    
    /**
     * Create a new message instance.
     *
     * @param array $details フォームから受け取ったデータ
     */
    public function __construct(array $details)
    {
        // 受け取ったデータを $this->details に代入
        $this->details = $details; 
    }

    /**
     * Build the message. (Laravel 8以前の形式)
     *
     * @return $this
     */
    public function build() //
    {
        // 件名とビューファイルを指定
        return $this->subject('新しいお問い合わせ') // 💡 メール件名を設定
                    ->view('emails.contact'); // 💡 メール本文に使用するビューファイルを指定
    }
}