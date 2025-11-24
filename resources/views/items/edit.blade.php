<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品新規登録 - FrilClone</title>
    
    {{-- ★ Bootstrap 5.3 CDN を導入 ★ --}}
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
        .header a:hover { text-decoration: underline; }
        
        /* コンテンツエリアの調整 */
        .container { 
            padding-top: 40px; 
            padding-bottom: 40px;
        }
    </style>
</head>
<body>
    {{-- ヘッダー部分 (元のコードを再利用) --}}
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

<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- 成功メッセージの表示 (オプション) --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center fs-5 fw-bold">
                        「{{ $item->name }}」を編集する
                    </div>

                    <div class="card-body p-4">
                        {{-- PATCHメソッドを使って更新処理を行う --}}
                        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold">商品画像 (変更する場合のみ)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                        id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <div class="mt-3 text-center border p-2 rounded bg-light" id="image-preview-container">
                                    <h6 class="text-muted mb-2">現在の画像:</h6>
                                    {{-- 既存の画像を表示。新しい画像を選択すると、JavaScriptでこのimgタグのsrcが更新される --}}
                                    <img id="image-preview" 
                                          src="{{ $item->image_path ? Storage::url($item->image_path) : '' }}" 
                                          alt="画像プレビュー" 
                                          class="img-fluid rounded" 
                                          style="max-height: 250px; object-fit: contain;">
                                    @if (!$item->image_path)
                                        <p id="no-image-text" class="text-danger mt-2 mb-0">現在、画像が登録されていません。</p>
                                    @endif
                                </div>
                            </div>

                            <hr class="mb-4">

                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">商品名 (必須)</label>
                                {{-- old() 関数でバリデーションエラー時の値、または $item->name を表示 --}}
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" value="{{ old('name', $item->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-bold">カテゴリ (必須)</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                         id="category_id" name="category_id" required>
                                    <option value="">カテゴリを選択してください</option>
                                    @foreach ($categories as $category)
                                        {{-- $item->category_id と old('category_id') を比較して selected を設定 --}}
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="condition" class="form-label fw-bold">商品の状態 (必須)</label>
                                <select class="form-select @error('condition') is-invalid @enderror" 
                                         id="condition" name="condition" required>
                                    <option value="">状態を選択してください</option>
                                    @php $conditions = ['新品、未使用', '未使用に近い', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり']; @endphp
                                    @foreach ($conditions as $condition)
                                        <option value="{{ $condition }}" 
                                            {{ old('condition', $item->condition) == $condition ? 'selected' : '' }}>
                                            {{ $condition }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">商品説明 (必須)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" required>{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="price" class="form-label fw-bold">販売価格 (&yen;) (必須)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">&yen;</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                id="price" name="price" value="{{ old('price', $item->price) }}" required min="100">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label fw-bold">在庫数 (必須)</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                            id="stock" name="stock" value="{{ old('stock', $item->stock) }}" required min="1">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    変更を保存する
                                </button>
                            </div>
                        </form>
                        
                        <hr class="mt-4">
                        
                        <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('本当にこの商品を削除してもよろしいですか？');" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                この商品を削除する
                            </button>
                        </form>
                        
                        {{-- マイページに戻るリンクを追加 --}}
                        <div class="mt-3 text-center">
                            <a href="{{ route('mypage.index') }}" class="btn btn-link text-secondary">
                                &larr; マイページに戻る
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- スクリプトを追加 --}}
    <script>
    /**
     * ファイル選択時に画像をプレビューする関数
     * @param {Event} event 
     */
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image-preview');
            output.src = reader.result;
            // 画像が選択されたら「画像が登録されていません」のテキストを非表示にする
            const noImageText = document.getElementById('no-image-text');
            if (noImageText) {
                noImageText.style.display = 'none';
            }
        }
        // ファイルが選択されていれば読み込む
        if (event.target.files.length > 0) {
            reader.readAsDataURL(event.target.files[0]);
        } 
    }
    </script>
    {{-- Bootstrap JS CDN を導入 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>