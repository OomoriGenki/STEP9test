<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細 - FrilClone</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <style>
        /* 共通ヘッダーと基本スタイルの維持 */
        body { font-family: sans-serif; margin: 0; background-color: #f8f8f8; }
        .header { background-color: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .header a:hover { text-decoration: underline; }
        .logout-form button { background: none; border: none; color: white; cursor: pointer; font-size: 16px; font-weight: bold; padding: 0; margin-left: 20px; }

        /* 商品詳細コンテンツのスタイル */
        .content { 
            padding: 40px; 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
            margin-top: 30px;
        }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        
        .detail-section { margin-bottom: 20px; font-size: 1.1em; }
        .detail-section strong { display: inline-block; width: 80px; font-weight: bold; color: #555; }
        
        .image-container { text-align: center; margin: 30px 0; }
        .image-container img { 
            max-width: 100%; 
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .action-buttons { margin-top: 30px; display: flex; gap: 15px; align-items: center; }
        .add-to-cart-btn { 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 1em; 
            cursor: pointer; 
        }
        .back-btn { 
            padding: 10px 20px; 
            background-color: #6c757d; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 1em; 
            cursor: pointer; 
        }
        
        /* 📢 いいね機能用のCSS */
        .heart-icon { 
            color: #ccc; /* 未いいねは灰色 */
            font-size: 1.5em; 
            margin-right: 5px; 
            cursor: pointer; /* クリック可能であることを示す */
            transition: color 0.2s;
        }
        .heart-icon.liked {
            color: red; /* いいね済は赤色 */
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="{{ route('products.index') }}">FrilClone</a>
        </div>
        <nav>
            @auth
                <a href="{{ route('mypage.index') }}">マイページ</a>
                <a href="{{ route('items.create') }}">出品</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;" class="logout-form">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('register') }}">新規登録</a>
                <a href="{{ route('contact.create') }}">お問い合わせ</a>
            @endauth
        </nav>
    </header>

    <div class="content">
        <h1>商品詳細</h1>
        
        @php
            // ダミーデータ (ProductControllerから渡されることを想定)
            // 実際は $product = Item::find($id); のようなEloquentモデルインスタンスが渡されます。
            $product = [
                'id' => $id ?? 123, // 📢 IDはURLパラメータから渡る $id を使用することを想定
                'name' => 'タブレット',
                'description' => 'これは最新のタブレットです。',
                'price' => 25000,
                'company' => 'TNG',
                // 📢 is_liked と likes_count はモデルのアクセサから取得することを想定
                'is_liked' => false, 
                'likes_count' => 42, 
                'image_url' => 'dummy_tablet.png'
            ];
        @endphp

        <div class="detail-section">
            <strong>商品名 :</strong> {{ $product['name'] }}
        </div>

        <div class="detail-section">
            <strong>説明 :</strong> {{ $product['description'] }}
        </div>

        <div class="image-container">
            <strong>画像 :</strong>
            
        </div>

        <div class="detail-section">
            <strong>金額 :</strong> ¥{{ number_format($product['price']) }}
        </div>

        <div class="detail-section">
            <strong>会社 :</strong> {{ $product['company'] }}
        </div>

        <div class="detail-section">
            <span 
                class="heart-icon @if ($product['is_liked']) liked @endif" 
                id="like-icon" 
                data-item-id="{{ $product['id'] }}" 
                data-is-liked="{{ $product['is_liked'] ? 'true' : 'false' }}"
                
                data-is-logged-in="{{ Auth::check() ? 'true' : 'false' }}"
            >
                ❤️
            </span>
            <span id="likes-count">{{ $product['likes_count'] }}</span> Likes
        </div>

        <div class="action-buttons">
            <a href="#" class="add-to-cart-btn">カートに追加する</a>
            <a href="{{ route('products.index') }}" class="back-btn">戻る</a>
        </div>
    </div>
    
    <script src="{{ asset('js/like.js') }}" defer></script>
</body>
</html>