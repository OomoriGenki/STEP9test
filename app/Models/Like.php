<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    
    // 中間テーブルなので、user_idとitem_idのみを許可
    protected $fillable = [
        'user_id',
        'item_id',
    ];

    /**
     * Likeは一人のUserに紐づく (多対一)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Likeは一つのItemに紐づく (多対一)
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}