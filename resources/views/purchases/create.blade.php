<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入画面 - {{ $item->name }}</title>
    
    <style>
        /* 共通ヘッダーと基本スタイルの維持 */
        body { font-family: sans-serif; margin: 0; background-color: #f8f8f8; }
        .header { background-color: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .header a:hover { text-decoration: underline; }
        .logout-form button { background: none; border: none; color: white; cursor: pointer; font-size: 16px; font-weight: bold; padding: 0; margin-left: 20px; }

        .container { 
            padding: 40px; 
            max-width: 600px; 
            margin: 30px auto; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
            text-align: left; /* コンテンツを左寄せに戻す */
        }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; font-size: 2em; }
        
        .product-info { margin-bottom: 20px; }
        .product-info p { margin: 5px 0; }
        
        /* 画像を商品情報の下に配置し、中央寄せにする */
        .product-visual { text-align: center; margin-bottom: 20px; }
        .product-visual img { 
            max-width: 60%; /* 画像を小さくする */
            height: auto; 
            border-radius: 8px; 
        }

        /* 数量入力、金額、在庫情報のセクション */
        .form-details { margin-top: 20px; }
        .form-group { margin-bottom: 15px; display: block; } /* 数量を単独行に */
        .form-group input[type="number"] { width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em; }

        .detail-row { margin-bottom: 10px; font-size: 1.1em; }
        .price { font-size: 1.3em; color: #333; font-weight: bold; }
        .label { display: inline-block; width: 60px; color: #555; }

        .action-buttons { margin-top: 30px; display: flex; gap: 10px; }
        .buy-button { 
            padding: 10px 20px; 
            background-color: #007bff; /* 青色 */
            color: white; 
            border: none; 
            border-radius: 4px; 
            font-size: 1em; 
            cursor: pointer; 
        }
        .back-button { 
            padding: 10px 20px; 
            background-color: #6c757d; /* 灰色 */
            color: white; 
            border: none; 
            border-radius: 4px; 
            font-size: 1em; 
            text-decoration: none;
            cursor: pointer; 
            display: inline-block;
        }
    </style>
</head>
<body>
    {{-- ヘッダーは前回の修正版から変更なし --}}
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">FrilClone</a>
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
            @endauth
        </nav>
    </header>

    <div class="container">
        <h1>購入画面</h1>

        {{-- エラーメッセージの表示 --}}
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @if (session('error'))
            <div style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- 商品名と説明 (画像上部) --}}
        <div class="product-info">
            <p style="font-size: 1.2em; font-weight: bold;">商品名 : {{ $item->name }}</p>
            <p style="color: #666;">説明 : {{ $item->description }}</p>
        </div>

        {{-- 画像表示 --}}
        <div class="product-visual">
            @if ($item->image_path)
                {{-- ここでは画像イメージが中央に配置されるように調整 --}}
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}の画像"> 
            @else
                <div style="width: 200px; height: 200px; background: #eee; margin: 0 auto; display: flex; align-items: center; justify-content: center;">画像なし</div>
            @endif
        </div>

        <form action="{{ route('purchases.store', $item) }}" method="POST" class="form-details">
            @csrf
            
            {{-- 1. 数量入力 (画像に合わせてシンプルに) --}}
            <div class="form-group">
                {{-- ラベルを削除し、入力フィールドのみを配置 (画像に合わせる) --}}
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                       min="1" max="{{ $item->stock }}" required>
            </div>
            
            {{-- 2. 金額 --}}
            <div class="detail-row">
                <span class="label">金額 :</span>
                <span class="price">¥{{ number_format($item->price) }}</span>
            </div>
            
            {{-- 3. 残り在庫 --}}
            <div class="detail-row">
                <span class="label">残り :</span> {{ $item->stock }}
            </div>
            
            {{-- 4. 出品者 --}}
            <div class="detail-row">
                <span class="label">会社 :</span> {{ $item->user->name ?? '不明' }}
            </div>

            <div class="action-buttons">
                <button type="submit" class="buy-button" @if ($item->stock <= 0) disabled style="opacity: 0.6;" @endif>
                    購入する
                </button>
                <a href="{{ route('items.show', $item) }}" class="back-button">
                    戻る
                </a>
            </div>
        </form>
    </div>
</body>
</html>