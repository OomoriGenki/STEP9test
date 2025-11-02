<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // 特定ユーザーのいいね確認のために追加

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'company',
        'image_path',
    ];

    /**
     * ItemはUserに属する (多対一)
     * どのユーザーが出品したか
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itemは複数のLikeを持つ (一対多)
     * 1つの商品に対して「いいね」は複数
     */
    public function likes()
    {
        // 画像の内容をItemモデルに合わせて修正
        return $this->hasMany(Like::class);
    }

    // 【提案】いいね総数を取得するアクセサを追加
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * 特定のユーザーがこの商品に「いいね」をしているかどうかを判定するアクセサ
     */
    public function getIsLikedAttribute()
    {
        if (Auth::guest()) {
            return false;
        }
        // 現在ログインしているユーザーがこの商品にいいねしているかチェック
        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}