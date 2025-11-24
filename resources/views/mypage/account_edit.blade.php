<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント情報編集 - FrilClone</title>
    
    {{-- Bootstrap 5.3 CDN を導入 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: sans-serif; 
            margin: 0; 
            background-color: #f7f7f7; 
        }
        .header { 
            background-color: #333; 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .header a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            font-weight: bold; 
        }
        
        .container { 
            padding-top: 40px; 
            padding-bottom: 40px;
        }

        /* 画像のデザインに近づけるためのカスタムスタイル (image_a2e1c1.png) */
        .card-header-custom {
            font-size: 2rem; 
            font-weight: bold;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .form-control-static {
            padding: .375rem .75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            background-color: #e9ecef; /* 背景色を付けて静的な入力欄のように見せる */
            pointer-events: none; /* クリック不可にする */
        }
    </style>
</head>
<body>
    {{-- ヘッダー部分 --}}
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">FrilClone</a>
        </div>
        <nav>
            {{-- 認証済みユーザーのヘッダーナビゲーション --}}
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="card-header-custom">
                    アカウント情報編集
                </div>

                {{-- 成功メッセージ --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="card p-4 shadow-sm">
                    {{-- POSTメソッドで更新処理を行う --}}
                    <form method="POST" action="{{ route('mypage.updateAccount') }}">
                        @csrf
                        
                        {{-- ユーザー名 (静的表示) --}}
                        <div class="mb-3">
                            <label for="name" class="form-label text-muted">ユーザ名</label>
                            <div class="form-control-static">{{ Auth::user()->name }}</div>
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        </div>

                        {{-- Eメール (静的表示) --}}
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">Eメール</label>
                            <div class="form-control-static">{{ Auth::user()->email }}</div>
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        </div>

                        <hr class="mt-4 mb-4">

                        {{-- 名前 (プロフィール情報) --}}
                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold">名前</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                    id="full_name" name="full_name" 
                                    value="{{ old('full_name', Auth::user()->profile->full_name ?? '') }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- カナ (プロフィール情報) --}}
                        <div class="mb-4">
                            <label for="full_name_kana" class="form-label fw-bold">カナ</label>
                            <input type="text" class="form-control @error('full_name_kana') is-invalid @enderror" 
                                    id="full_name_kana" name="full_name_kana" 
                                    value="{{ old('full_name_kana', Auth::user()->profile->full_name_kana ?? '') }}">
                            @error('full_name_kana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-start">
                            {{-- 戻るボタン --}}
                            <a href="{{ route('mypage.index') }}" class="btn btn-secondary me-2">
                                戻る
                            </a>
                            {{-- 更新ボタン --}}
                            <button type="submit" class="btn btn-primary">
                                更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bootstrap JS CDN を導入 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>