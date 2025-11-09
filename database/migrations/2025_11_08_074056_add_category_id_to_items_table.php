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
        Schema::table('items', function (Blueprint $table) {
            // category_id カラムを追加し、nullable (null許容) に設定
            // constrained('categories') で categories テーブルを参照する外部キー制約を設定
            // onDelete('set null') は、参照先のカテゴリが削除された場合に、この category_id を NULL に設定します
            $table->foreignId('category_id')
                  ->nullable() 
                  ->constrained('categories') 
                  ->onDelete('set null') 
                  ->after('user_id'); // user_id カラムの直後に追加（位置は任意）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // 外部キー制約を先に削除
            $table->dropConstrainedForeignId('category_id');
            
            // カラムを削除
            // $table->dropColumn('category_id');
        });
    }
};
