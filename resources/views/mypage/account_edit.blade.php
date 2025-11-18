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
    <div class="col-md-7">
        <h1 class="text-center mb-4 fw-bold text-primary">プロフィール編集</h1>

        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                
                {{-- セッションメッセージの表示 --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- フォームの送信先はUserControllerのupdateAccountメソッド --}}
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    
                    {{-- 1. 名前 --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">ユーザー名 (必須)</label>
                        <input id="name" type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                        
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- 2. メールアドレス --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">メールアドレス (必須)</label>
                        <input id="email" type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" required autocomplete="email">
                        
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    {{-- 3. （オプション）パスワード変更リンク --}}
                    <div class="mb-4 text-end">
                        <small>
                            <a href="#">パスワードを変更する方はこちら</a>
                        </small>
                    </div>

                    {{-- 4. 更新ボタン --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow">
                            プロフィールを更新
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('mypage.index') }}" class="text-muted text-decoration-none">
                        &larr; マイページに戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>