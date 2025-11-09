<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 💡 Auth ファサードは不要になるため削除

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
        'stock', // 💡 修正点: 'stock' カラムを追加
    ];

    /**
     * ItemはUserに属する (多対一)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itemは複数のLikeを持つ (一対多)
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // 購入リレーションも追加
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * いいね総数を取得するアクセサ
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * 💡 修正点: 特定のユーザーがこの商品に「いいね」をしているかどうかを判定するメソッド
     * @param int|null $userId
     * @return bool
     */
    public function isLikedByUser($userId = null)
    {
        // $userIdがnullの場合は false を返す (コントローラー側で Auth::id() を渡すことを前提とする)
        if (is_null($userId)) {
             return false;
        }
        
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function category()
    {
    return $this->belongsTo(Category::class);
    }
}