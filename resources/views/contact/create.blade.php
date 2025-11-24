<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム - FrilClone</title>
    {{-- Bootstrap 5.3 CDN を導入 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; background-color: #f7f7f7; padding-top: 50px; }
        .contact-container { max-width: 600px; margin: 0 auto; padding: 30px; background-color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-label { font-weight: bold; margin-bottom: 5px; display: block; }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
        .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    </style>
</head>
<body>
    <div class="contact-container">
        <h1 class="mb-4 text-center">お問い合わせフォーム</h1>

        {{-- 成功メッセージの表示 --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- バリデーションエラーの表示 --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            {{-- 名前 --}}
            <div class="mb-3">
                <label for="name" class="form-label">名前</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            {{-- メールアドレス --}}
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            {{-- お問い合わせ内容 --}}
            <div class="mb-4">
                <label for="content" class="form-label">お問い合わせ内容</label>
                <textarea class="form-control" id="content" name="content" rows="6" required>{{ old('content') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-50 me-2">送信</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary w-50 ms-2">戻る</a>
            </div>
        </form>
    </div>

    {{-- Bootstrap JS CDN を導入 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>