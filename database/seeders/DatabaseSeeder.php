<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
// Fakerのuseは不要になるので削除しても良いですが、ここでは残します。
use Faker\Factory as Faker; 
use Illuminate\Support\Facades\DB; // IDを明示的に設定する場合に備えて追加

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // CategorySeederの呼び出しはOK
        $this->call([
            CategorySeeder::class,
        ]);
        
        // 1. 最初にテストユーザーを作成し、$testUser に格納する (必須)
        $testUser = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'last_name_kanji' => '山田',
            'first_name_kanji' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
        ]);
        
        // ★★★ 修正箇所: 画像の固定データを挿入する ★★★
        $fixedItems = [
            // category_idは CategorySeeder の投入順（1から6）を想定
            ['id' => 1, 'name' => '鉛筆', 'description' => '描きやすい鉛筆です', 'price' => 200, 'category_id' => 1, 'image_path' => 'images/items/pencil.png'],
            ['id' => 3, 'name' => 'イヤホン', 'description' => 'ワイヤレスです。', 'price' => 1000, 'category_id' => 3, 'image_path' => 'images/items/earphone.png'],
            ['id' => 4, 'name' => 'タブレット', 'description' => '軽量です', 'price' => 25000, 'category_id' => 3, 'image_path' => 'images/items/tablet.png'],
            ['id' => 5, 'name' => 'デスク', 'description' => '昇降できます', 'price' => 30000, 'category_id' => 5, 'image_path' => 'images/items/desk.png'],
        ];

        // 固定データ挿入前にIDの自動採番カウンタをリセット (IDを明示的に設定しないため)
        // Item::truncate(); // migrate:fresh で既に削除されているため不要

        foreach ($fixedItems as $item) {
            // IDはDBの自動採番に任せるため、配列から除外
            unset($item['id']);
            
            Item::create(array_merge($item, [
                'user_id' => $testUser->id,
                'stock' => 1,
                'condition' => '新品', // condition は必須と想定
            ]));
        }
    }
}