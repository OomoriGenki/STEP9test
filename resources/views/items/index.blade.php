<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧 - FrilClone</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f8f8f8; }
        .header { background-color: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .header a:hover { text-decoration: underline; }
        
        .content { padding: 40px; max-width: 1200px; margin: 0 auto; }
        
        /* --- 検索フォームのスタイル --- */
        .search-form { 
            display: flex; 
            gap: 10px; 
            margin-bottom: 30px; 
            align-items: center;
        }
        .search-form input[type="text"] { 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px;
        }
        .search-form input[name="product_name"] {
            flex-grow: 1;
            max-width: 300px;
        }
        .search-form input[name="min_price"],
        .search-form input[name="max_price"] {
            width: 120px;
            text-align: right;
        }
        .search-form button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form span {
            font-weight: bold;
        }

        /* --- 商品一覧テーブルのスタイル --- */
        .product-list table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-list th, .product-list td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .product-list th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .product-list td.item-image {
            text-align: center;
            width: 100px;
        }
        .product-list td.item-image img {
            width: 80px; 
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .product-list .detail-button {
            background-color: #28a745; /* 緑色 */
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        
        /* ログイン/ログアウトボタンのスタイル (ヘッダー内) */
        .logout-form button { 
            background: none; 
            border: none; 
            color: white; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
            padding: 0; 
            margin-left: 20px;
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
        <h1>商品一覧</h1>
        
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
            <input type="text" name="product_name" placeholder="商品名を入力" value="{{ request('product_name') }}">
            
            <input type="text" name="min_price" placeholder="最低価格" value="{{ request('min_price') }}">
            <span>〜</span>
            <input type="text" name="max_price" placeholder="最高価格" value="{{ request('max_price') }}">
            
            <button type="submit">検索</button>
        </form>

        <div class="product-list">
            <table>
                <thead>
                    <tr>
                        <th>商品番号</th>
                        <th>商品名</th>
                        <th>商品説明</th>
                        <th>画像</th>
                        <th>料金(¥)</th>
                        <th></th> </tr>
                </thead>
                <tbody>
                    @php
                        // 画面レイアウトのデータ
                        $products = [
                            ['id' => 1, 'name' => '鉛筆', 'description' => '描きやすい鉛筆です', 'price' => 200, 'image_url' => 'dummy_pencil.png'],
                            ['id' => 3, 'name' => 'イヤホン', 'description' => 'ワイヤレスです', 'price' => 1000, 'image_url' => 'dummy_earphone.png'],
                            ['id' => 4, 'name' => 'タブレット', 'description' => '軽量です', 'price' => 25000, 'image_url' => 'dummy_tablet.png'],
                            ['id' => 5, 'name' => 'デスク', 'description' => '昇降できます', 'price' => 30000, 'image_url' => 'dummy_desk.png'],
                        ];
                    @endphp

                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product['id'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['description'] }}</td>
                        <td class="item-image">
                                                    </td>
                        <td>{{ number_format($product['price']) }}</td>
                        <td>
                            <a href="{{ route('products.show', $product['id']) }}" class="detail-button">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>