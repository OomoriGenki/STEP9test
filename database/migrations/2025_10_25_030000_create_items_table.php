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
            $table->string('condition')->default('新品');
            
            // 外部キー: user_id は必須かつユーザー削除時に商品も削除
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            
            // 商品情報
            $table->string('name', 255); // 文字数制限を追加 (任意)
            $table->text('description');
            
            // 💡 価格を unsignedInteger に修正
            $table->unsignedInteger('price');
            
            $table->string('company')->nullable(); 
            $table->string('image_path')->nullable(); 
            
            // 💡 在庫数の初期値を 1 に修正 (任意)
            $table->integer('stock')->default(1); 

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