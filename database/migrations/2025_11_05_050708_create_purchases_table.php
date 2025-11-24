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
        
        // 【修正点】 user_id を buyer_id に変更
        // これは、購入者（User）を指す外部キーであることを明確にします。
        $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); 
        
        // item_id はアイテムへのリレーションとしてそのまま残します。
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
        
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