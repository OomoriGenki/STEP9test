<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; // モデルの使用を明示

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 💡 修正箇所: 必須の氏名関連カラムを追加
        $lastName = fake()->lastName(); // ダミーの姓
        $firstName = fake()->firstName(); // ダミーの名

        return [
            // name は、姓と名を連結してそのまま使用
            'name' => $lastName . ' ' . $firstName, 
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            
            // 必須の氏名カラムを追加
            'last_name_kanji' => $lastName,
            'first_name_kanji' => $firstName,
            // カナは Faker に対応するメソッドがない場合があるため、仮の値を設定するか、別途日本語Fakerライブラリを使用
            'last_name_kana' => 'ヤマダ', // 仮の値
            'first_name_kana' => 'タロウ', // 仮の値
            
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}