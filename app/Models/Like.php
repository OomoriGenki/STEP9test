<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // Likesテーブルは user_id と item_id のみで構成され、IDとタイムスタンプは自動
    protected $fillable = [
        'user_id',
        'item_id',
    ];
    
    // Likesテーブルが belongsTo で Item (商品) と User (ユーザー) に紐づく

    /**
     * Likeは1つのItemに属する (多対一)
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Likeは1つのUserに属する (多対一)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}