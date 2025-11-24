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
        .search-form input[name="keyword"] {
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
            white-space: nowrap;
        }
        /* 各ヘッダーの幅を明示的に設定して、1行に収まるようにする */
        .product-list thead th:nth-child(1) { /* 商品番号 */
            width: 120px;
        }
        .product-list thead th:nth-child(5) { /* 料金(¥) */
            width: 100px;
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

        /* --- ★ 追加: フッタースタイル ★ --- */
        .footer {
            background-color: #333;
            color: white;
            padding: 20px 30px;
            text-align: center;
            width: 100%;
            box-sizing: border-box; /* paddingを含めて幅100%にする */
        }
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* スマホ表示対策 */
        }
        .footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 15px;
            border: 1px solid white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .footer a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">FrilClone</a>
        </div>
        <nav>
            @auth
                <a href="{{ route('mypage.index') }}">マイページ</a>
                <a href="{{ route('items.create') }}">出品</a>
                <span class="user-name">
                    ログインユーザー: {{ Auth::user()->name }}
            </span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
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
        
        <form action="{{ route('items.index') }}" method="GET" class="search-form">
            
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <input type="text" name="keyword" placeholder="商品名を入力" value="{{ request('keyword') }}">
            
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
                    {{-- ★ 静的データ $products を削除し、コントローラから渡される $items を使用 ★ --}}
                    @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="item-image">
                            {{-- 修正: Storage::url() を asset() に変更 --}}
                        <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
                        </td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>
                            {{-- ★ route('products.show') を route('items.show') に修正 ★ --}}
                            <a href="{{ route('items.show', $item) }}" class="detail-button">詳細</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">現在、該当する商品はありません。</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ページネーションリンクを追加 --}}
        <div class="mt-4">
            {{ $items->appends(request()->query())->links('pagination::simple-default') }}
        </div>
    </div>
    {{-- ★ 追加: フッターセクション ★ --}}
    <footer class="footer">
        <div class="footer-content">
            <div>
                &copy; {{ date('Y') }} FrilClone. All Rights Reserved.
            </div>
            <div>
                {{-- お問い合わせボタン --}}
                <a href="{{ route('contact.create') }}" style="border-color: #007bff; background-color: #007bff;">
                    お問い合わせ
                </a>
            </div>
        </div>
    </footer>
</body>
</html>