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
            <h1 class="text-center mb-4 fw-bold">商品を新しく出品する</h1>
            
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    {{-- フォームの送信先はitems.store --}}
                    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- 1. 商品画像 (必須) --}}
                        <div class="mb-4">
                            <label for="image_path" class="form-label fw-bold">商品画像 (必須)</label>
                            {{-- ★修正: name="image_path" に変更し、コントローラーと合わせる★ --}}
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" 
                                   id="image_path" name="image_path" required accept="image/*" onchange="previewImage(event)">
                            
                            @error('image_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-3 text-center border p-2 rounded bg-light" id="image-preview-container" style="display: none;">
                                <img id="image-preview" src="#" alt="画像プレビュー" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                            </div>
                        </div>

                        <hr class="mb-4">

                        {{-- 2. 商品名 (必須) --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">商品名 (必須)</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required placeholder="例: 限定版スニーカー 27.5cm">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. カテゴリ (必須) --}}
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-bold">カテゴリ (必須)</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">カテゴリを選択してください</option>
                                {{-- $categoriesはコントローラーから渡されるデータ --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 4. 商品の状態 (必須) --}}
                        <div class="mb-4">
                            <label for="condition" class="form-label fw-bold">商品の状態 (必須)</label>
                            <select class="form-select @error('condition') is-invalid @enderror" 
                                    id="condition" name="condition" required>
                                <option value="">状態を選択してください</option>
                                <option value="新品、未使用" {{ old('condition') == '新品、未使用' ? 'selected' : '' }}>新品、未使用</option>
                                <option value="未使用に近い" {{ old('condition') == '未使用に近い' ? 'selected' : '' }}>未使用に近い</option>
                                <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                                <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                                <option value="傷や汚れあり" {{ old('condition') == '傷や汚れあり' ? 'selected' : '' }}>傷や汚れあり</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 5. 商品説明 (必須) --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">商品説明 (必須)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required placeholder="商品の詳細、購入時期、使用回数などを詳しく記述してください。">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            {{-- 6. 販売価格 (必須) --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="price" class="form-label fw-bold">販売価格 (&yen;) (必須)</label>
                                <div class="input-group">
                                    <span class="input-group-text">&yen;</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required min="100" placeholder="10000">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            {{-- 7. 在庫数 (必須) --}}
                            <div class="col-md-6">
                                <label for="stock" class="form-label fw-bold">在庫数 (必須)</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', 1) }}" required min="1">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                出品する
                            </button>
                            <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-lg">
                                &lt; 商品一覧に戻る
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * ファイル選択時に画像をプレビューする関数
 * @param {Event} event 
 */
function previewImage(event) {
    // IDをimage_pathに合わせる
    const file = event.target.files[0];
    const output = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(){
            output.src = reader.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        output.src = '#'; 
        container.style.display = 'none';
    }
}
</script>
{{-- Bootstrap JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>