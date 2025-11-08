<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
// use App\Models\Like; // 必要に応じて追加

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. 最初にテストユーザーを作成し、$testUser に格納する (必須)
        $testUser = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'last_name_kanji' => '山田',
            'first_name_kanji' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            // 他の必須カラムはUserFactoryで定義済み
        ]);
        
        // 2. 作成した $testUser を使って Item を作成する
        // 以前のコードにあった 'user_id' => $testUser->id の参照エラーがこれで解消されます。
        Item::factory(10)->create([
            'user_id' => $testUser->id,
        ]);
        
        // 3. 追加で他のダミーデータを生成する（任意）
        // Item::factory(10)->create(['user_id' => 1]); の行は、重複作成になるため削除または修正します。
        
        // 他のユーザーにも出品させる場合は、別のダミーユーザーを作成する
        User::factory(5)->create()->each(function ($user) {
             Item::factory(5)->create(['user_id' => $user->id]);
        });
        
        // 必要に応じて Like::factory()->create(); などを追加
    }
}