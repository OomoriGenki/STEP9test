<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Mass Assignment を許可するフィールドを定義 (UserContrllerのupdateAccountに合わせる)
    protected $fillable = [
        'user_id', // Userモデルとのリレーションキー
        'full_name',
        'full_name_kana',
    ];

    /**
     * Profileは単一のUserに属する (多対一)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}