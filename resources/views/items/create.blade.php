@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center fs-5 fw-bold">商品を新しく出品する</div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">商品画像 (必須)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" required accept="image/*" onchange="previewImage(event)">
                        
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="mt-3 text-center border p-2 rounded bg-light" id="image-preview-container" style="display: none;">
                            <img id="image-preview" src="#" alt="画像プレビュー" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">商品名 (必須)</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required placeholder="例: 限定版スニーカー 27.5cm">
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">商品説明 (必須)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" required placeholder="商品の詳細、購入時期、使用回数などを詳しく記述してください。">{{ old('description') }}</textarea>
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
                                       id="price" name="price" value="{{ old('price') }}" required min="100" placeholder="10000">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * ファイル選択時に画像をプレビューする関数
 * @param {Event} event 
 */
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');
        output.src = reader.result;
        container.style.display = 'block';
    }
    // ファイルが選択されていれば読み込む
    if (event.target.files.length > 0) {
        reader.readAsDataURL(event.target.files[0]);
    } else {
        document.getElementById('image-preview-container').style.display = 'none';
    }
}
</script>
@endpush