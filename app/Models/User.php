<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name_kanji', 
        'first_name_kanji', 
        'last_name_kana', 
        'first_name_kana', 
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Userは複数のItemを出品できる (一対多)
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Userは単一のProfileを持つ (一対一) ★ 追加
     */
    public function profile()
    {
        // Profileモデルが存在し、user_idカラムを持っていることを前提とする
        return $this->hasOne(Profile::class); 
    }

    /**
     * Userは複数のLikeを持つ (一対多)
     * どの商品にいいねしたかを確認できる
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    /**
     * Userは複数のPurchaseを持つ（購入履歴） (一対多)
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    public function likedItems()
    {
    // usersテーブルとitemsテーブルをlikesテーブルでつなぐ
    return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id');
    }
}