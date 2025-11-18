<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator; // ★ Paginatorを追加 ★

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ★ ページネーションにBootstrapのビューを使用する設定を追加 ★
        Paginator::useBootstrap();
        
        // マイグレーションエラー回避設定は削除を推奨
        // Schema::disableForeignKeyConstraints();
    }
}