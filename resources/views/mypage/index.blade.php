<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ - FrilClone</title>
    
    {{-- ★ 追加: Bootstrap 5.3 CDN を導入 (Bootstrap クラスを使用しているため必須) ★ --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f8f8f8; }
        .header { background-color: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .header a:hover { text-decoration: underline; }
        
        /* content ラッパーを定義して、上下の余白と幅を設定 */
        .page-content { 
            padding: 40px 20px; 
            max-width: 1200px; 
            margin: 0 auto; 
            min-height: calc(100vh - 120px); /* フッターのために最小高を調整 */ 
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
            box-sizing: border-box; 
            margin-top: 30px; /* 上部のコンテンツとの間隔 */
        }
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .footer a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .footer a.contact-btn {
             /* お問い合わせボタンを primary カラーっぽく強調 */
            background-color: #0d6efd; 
            border-color: #0d6efd;
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

{{-- ★ 追加: ページコンテンツラッパー ★ --}}
    <div class="page-content"> 
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
                            <div class="user-info-right">
                                <p class="mb-1"><strong>名前:</strong> {{ Auth::user()->profile->full_name ?? '未設定' }}</p> 
                                <p class="mb-1"><strong>カナ:</strong> {{ Auth::user()->profile->full_name_kana ?? '未設定' }}</p> 
                            </div>
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
                                <td><a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-info text-white">詳細</a></td>
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
                            @forelse($purchases as $purchase)
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
    </div> 
    {{-- ★ 終了: ページコンテンツラッパー ★ --}}

    {{-- ★ 追加: フッター部分 ★ --}}
    <footer class="footer">
        <div class="footer-content">
            <div>
                &copy; {{ date('Y') }} FrilClone. All Rights Reserved.
            </div>
            <div>
                {{-- お問い合わせボタン --}}
                <a href="{{ route('contact.create') }}" class="contact-btn">
                    お問い合わせ
                </a>
            </div>
        </div>
    </footer>
    
    {{-- ★ 追加: Bootstrap JS CDN を導入 ★ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>