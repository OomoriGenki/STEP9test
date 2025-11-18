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

<div class="row justify-content-center">
    <div class="col-md-10">
        <h1 class="text-start mb-4 fw-bold">マイページ</h1>
        
        <div class="card shadow-sm border-0 mb-5 p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                {{-- アカウント編集ボタン --}}
                <a href="{{ route('mypage.editAccount') }}" class="btn btn-primary btn-sm">アカウント編集</a> 
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    {{-- ユーザー名とEメールは左側に表示 --}}
                    <p class="mb-1"><strong>ユーザ名:</strong> {{ Auth::user()->name }}</p>
                    <p class="mb-1"><strong>Eメール:</strong> {{ Auth::user()->email }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    {{-- 名前とカナは右側に表示 --}}
                    <div class="col-md-6 user-info-right">
                            <p class="mb-1"><strong>名前:</strong> {{ Auth::user()->profile->full_name ?? '未設定' }}</p> 
                            <p class="mb-1"><strong>カナ:</strong> {{ Auth::user()->profile->full_name_kana ?? '未設定' }}</p> 
                    </div>
            </div>
        </div>
        
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">＜出品商品＞</h2>
                <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">新規登録</a> 
            </div>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>商品番号</th>
                        <th>商品名</th>
                        <th>商品説明</th>
                        <th>料金(￥)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->description, 30) }}</td>
                        <td>{{ number_format($item->price) }}</td>
                        <td><a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info text-white">詳細</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">現在、出品中の商品はありません。</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mb-5">
            <h2 class="h5 fw-bold mb-3">＜購入した商品＞</h2>
            
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>商品説明</th>
                        <th>料金(￥)</th>
                        <th>個数</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchasedItems as $purchase)
                    <tr>
                        {{-- $purchase->item から商品情報を取得 --}}
                        <td>{{ $purchase->item->name }}</td>
                        <td>{{ Str::limit($purchase->item->description, 30) }}</td>
                        <td>{{ number_format($purchase->price) }}</td> {{-- 購入時の価格 $purchase->price --}}
                        <td>{{ $purchase->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">購入した取引がありません。</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>