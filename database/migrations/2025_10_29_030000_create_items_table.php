<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            
            // 外部キー: どのユーザーが出品したか
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 商品情報
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->string('company')->nullable(); // 会社名（必須でなければnullable）
            $table->string('image_path')->nullable(); // 画像のパス
            
            // 在庫数（購入機能のために追加）
            $table->integer('stock')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};