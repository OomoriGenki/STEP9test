<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションを実行する (テーブル作成)
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // カテゴリID (主キー、自動増分)
            
            // カテゴリ名 (例: 'ファッション', '家電')
            $table->string('name')->unique(); 
            
            // URLフレンドリーな文字列 (例: 'fashion', 'electronics')
            // 検索エンジン最適化やURL設計に利用される
            $table->string('slug')->unique(); 
            
            $table->timestamps(); // created_at と updated_at カラム
        });
    }

    /**
     * マイグレーションを元に戻す (テーブル削除)
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};