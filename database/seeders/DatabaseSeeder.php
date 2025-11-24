<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase; // Purchaseモデルのuseを追加

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // CategorySeederの呼び出し
        $this->call([
            CategorySeeder::class,
        ]);
        
        // 1. テストユーザーの作成 (出品者と購入者を分離)
        
        // 【出品者ユーザー】: 全ての商品を出品する
        $sellerUser = User::factory()->create([
            'name' => '出品者ユーザー',
            'email' => 'seller@example.com',
            'last_name_kanji' => '山田',
            'first_name_kanji' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
        ]);
        
        // 【購入者ユーザー】: ログインしてマイページを見るテスト対象
        $buyerUser = User::factory()->create([
            'name' => '購入者ユーザー',
            'email' => 'buyer@example.com',
            'last_name_kanji' => '田中',
            'first_name_kanji' => '次郎',
            'last_name_kana' => 'タナカ',
            'first_name_kana' => 'ジロウ',
        ]);
        
        // 2. 固定データ（商品）を挿入する
        $fixedItems = [
            // ID記述を削除し、DB自動採番に任せる
            ['name' => '鉛筆', 'description' => '描きやすい鉛筆です', 'price' => 200, 'category_id' => 1, 'image_path' => 'images/items/pencil.png'],
            ['name' => 'イヤホン', 'description' => 'ワイヤレスです。', 'price' => 1000, 'category_id' => 3, 'image_path' => 'images/items/earphone.png'],
            ['name' => 'タブレット', 'description' => '軽量です', 'price' => 25000, 'category_id' => 3, 'image_path' => 'images/items/tablet.png'],
            ['name' => 'デスク', 'description' => '昇降できます', 'price' => 30000, 'category_id' => 5, 'image_path' => 'images/items/desk.png'],
        ];

        // 挿入されたItemのIDを追跡するための配列
        $createdItems = [];
        
        foreach ($fixedItems as $item) {
            $createdItems[] = Item::create(array_merge($item, [
                'user_id' => $sellerUser->id, // 出品者を sellerUser に固定
                'stock' => 1,
                'condition' => '新品', 
            ]));
        }

        // 3. 購入履歴（Purchase）データを挿入する (購入者を buyerUser に設定)
        
        // $createdItems には Itemモデルのインスタンスが順番に入っている
        // $createdItems[0] = 鉛筆 (ID: 1), $createdItems[1] = イヤホン (ID: 2), ...
        
        Purchase::create([
            'item_id' => $createdItems[0]->id, // 鉛筆
            'buyer_id' => $buyerUser->id,      // ★購入者を buyerUser に設定
            'price' => 200,
            'quantity' => 10,
            'status' => 'completed',
        ]);

        Purchase::create([
            'item_id' => $createdItems[1]->id, // イヤホン
            'buyer_id' => $buyerUser->id,
            'price' => 1000,
            'quantity' => 1,
            'status' => 'completed',
        ]);

        Purchase::create([
            'item_id' => $createdItems[2]->id, // タブレット
            'buyer_id' => $buyerUser->id,
            'price' => 25000,
            'quantity' => 2,
            'status' => 'completed',
        ]);

        Purchase::create([
            'item_id' => $createdItems[3]->id, // デスク
            'buyer_id' => $buyerUser->id,
            'price' => 30000,
            'quantity' => 5,
            'status' => 'completed',
        ]);
    }
}