<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemFactory extends Factory
{
    // ★ 修正: protected $model を設定し、protected $faker を日本語ロケールで定義する ★

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $this->faker は日本語データになる
        return [
            'name' => $this->faker->realText(15), // ★ 商品名として自然な短文を生成 ★
            'description' => $this->faker->realText(100),
            // 他の必須カラム...
            'price' => $this->faker->numberBetween(500, 50000),
            'stock' => 1,
            'condition' => $this->faker->randomElement(['新品', '中古', 'ジャンク']),
            'image_path' => 'images/items/dummy_' . $this->faker->numberBetween(1, 5) . '.png',
            'category_id' => $this->faker->numberBetween(1, 5),
            // 'user_id' はセーダー側で上書きされるためここでは省略可
        ];
    }
    
    // ★ 日本語のFakerインスタンスを返すメソッドを追加 (Laravel 9以降の書き方) ★
    public static function newFactory($count = null, $state = []): Factory
    {
        return parent::newFactory($count, $state)->locale('ja_JP');
    }
}