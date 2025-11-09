<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * テーブル名
     * @var string
     */
    protected $table = 'purchases';

    /**
     * 一括割り当て可能な属性
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'price',       // 購入時の価格を記録
        'quantity',    // 購入数量（フリマでは通常1）
        'status',      // 支払い状況や発送状況など
    ];

    /**
     * 購入者 (User) とのリレーションシップ
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入された商品 (Item) とのリレーションシップ
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}