<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item; // Itemモデルの使用を明示
use App\Models\User; // user_id の取得に必要

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            // 💡 修正箇所: 必須カラムにダミーデータを追加
            
            // 外部キーはシーダーで渡されることが多いが、ファクトリ内でも定義可能
            'user_id' => User::factory(), // ユーザーが未登録なら自動で作成する

            'name' => fake()->word() . ' ' . fake()->colorName(), // 商品名
            'description' => fake()->text(), // 商品説明
            'price' => fake()->numberBetween(100, 50000), // 価格 (100円から5万円)
            
            // 以前のマイグレーションで追加した任意/必須カラムも定義
            'company' => fake()->company(), 
            'image_path' => null, // 画像は省略
            'stock' => fake()->numberBetween(1, 10), // 在庫数
        ];
    }
}
