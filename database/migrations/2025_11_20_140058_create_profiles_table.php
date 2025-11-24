<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // Userモデルと連携するための外部キー (必須)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('full_name')->nullable();
            $table->string('full_name_kana')->nullable();
            
            $table->timestamps();

            // 1ユーザーにつき1プロフィールであることを保証
            $table->unique('user_id'); 
        });
    }
    // ...
};