<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * テーブル名
     * @var string
     */
    protected $table = 'categories';

    /**
     * 一括割り当て可能な属性
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug', // URLで使用するスラッグ
    ];

    /**
     * このカテゴリに属する商品 (Item) とのリレーションシップ
     * 1つのカテゴリは多数のアイテムを持つ (一対多)
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}