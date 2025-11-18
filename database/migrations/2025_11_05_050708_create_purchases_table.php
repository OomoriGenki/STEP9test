<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ★ 以下のコードでテーブルを作成していることを確認
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // user_id カラムはここで一緒に作成されるべきです
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('item_id')->constrained('items');
            $table->integer('price')->comment('購入時の価格');
            $table->integer('quantity')->default(1);
            $table->string('status')->default('completed')->comment('取引ステータス');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};