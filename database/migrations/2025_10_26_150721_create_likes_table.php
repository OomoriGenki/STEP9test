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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            
            //「商品(item)」への「いいね」として item_id を使用
            // constrained() で item_id を items テーブルの id に紐づけ、
            // onDelete('cascade') で商品削除時に「いいね」も削除
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            
            // ユーザーID: どのユーザーが「いいね」したか
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            
            // タイムスタンプ
            $table->timestamps(); //

            // 同じユーザーが同じ商品に複数回いいねできないように制約を追加
            $table->unique(['item_id', 'user_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};