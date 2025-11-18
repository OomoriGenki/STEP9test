<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category; // Categoryモデルを使用する場合

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 外部キーエラーを回避するため、Itemより先に実行
        
        $categories = [
            ['name' => 'レディース', 'slug' => 'ladies'],
            ['name' => 'メンズ', 'slug' => 'mens'],
            ['name' => '家電・スマホ・カメラ', 'slug' => 'electronics'],
            ['name' => 'おもちゃ・ホビー・グッズ', 'slug' => 'hobby'],
            ['name' => 'コスメ・香水・美容', 'slug' => 'cosmetics'],
            ['name' => 'その他', 'slug' => 'other'],
        ];

        // データベースにデータを挿入
        foreach ($categories as $category) {
            // Categoryモデルが作成済みであれば、Factoryやcreateを使用
            Category::firstOrCreate(
                ['slug' => $category['slug']], // 既に存在するかチェックするキー
                $category                     // 存在しない場合に作成するデータ
            );
            
            // または、シンプルなクエリビルダを使用
            // DB::table('categories')->insert($category);
        }
    }
}