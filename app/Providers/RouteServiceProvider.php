<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * アプリケーションの「ホーム」ルートへのパス。
     * ユーザーは認証後にここにリダイレクトされます。
     *
     * @var string
     */
    public const HOME = '/home'; // 💡 修正済み: ログイン後のリダイレクト先を /products に設定

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting(); // RateLimiter の設定を呼び出す

        $this->routes(function () {
            // API ルートの定義（通常、'api' ミドルウェアグループを適用）
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web ルートの定義（通常、'web' ミドルウェアグループを適用）
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            // APIルートのレート制限設定 (1分間に60回までなど)
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}