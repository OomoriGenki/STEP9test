<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 商品一覧、詳細表示を担当するコントローラー
class ProductController extends Controller
{
    /**
     * 商品一覧表示 (GET /)
     * ルート名: products.index
     */
    public function index(Request $request)
    {
        // 開発初期段階のため、ここでは認証状態に関わらず商品一覧ビューを返す。
        // 今後、検索/絞り込みのロジックや、商品データの取得処理を実装する。
        
        // 検索パラメーターの取得（実装予定）
        // $productName = $request->input('product_name');
        // $minPrice = $request->input('min_price');
        // $maxPrice = $request->input('max_price');
        
        // 商品一覧ビューを返す
        return view('products.index', [
            // 'products' => $products, // 今後追加予定
        ]);
    }

    /**
     * 商品詳細表示 (GET /products/{id})
     * ルート名: products.show
     */
    public function show($id)
    {
        // ここに特定の商品IDに対応する商品情報を取得するロジックを実装
        
        // 商品詳細ビューを返す
        return view('products.show', [
            'id' => $id,
            // 'product' => $product, // 今後追加予定
        ]);
    }
}