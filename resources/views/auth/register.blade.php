@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center fs-5">新規ユーザー登録</div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">表示名 (必須)</label>
                        <input id="name" type="text" placeholder="Resecco太郎" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <label class="form-label fw-bold">氏名 (漢字) (必須)</label>
                        <div class="col">
                            <input id="last_name_kanji" type="text" placeholder="姓" class="form-control @error('last_name_kanji') is-invalid @enderror" name="last_name_kanji" value="{{ old('last_name_kanji') }}" required>
                            @error('last_name_kanji')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input id="first_name_kanji" type="text" placeholder="名" class="form-control @error('first_name_kanji') is-invalid @enderror" name="first_name_kanji" value="{{ old('first_name_kanji') }}" required>
                            @error('first_name_kanji')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="form-label fw-bold">氏名 (カナ) (必須)</label>
                        <div class="col">
                            <input id="last_name_kana" type="text" placeholder="セイ (全角カタカナ)" class="form-control @error('last_name_kana') is-invalid @enderror" name="last_name_kana" value="{{ old('last_name_kana') }}" required>
                            @error('last_name_kana')
                                <div class="invalid-feedback">全角カタカナで入力してください。</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input id="first_name_kana" type="text" placeholder="メイ (全角カタカナ)" class="form-control @error('first_name_kana') is-invalid @enderror" name="first_name_kana" value="{{ old('first_name_kana') }}" required>
                            @error('first_name_kana')
                                <div class="invalid-feedback">全角カタカナで入力してください。</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">メールアドレス (必須)</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">パスワード (8文字以上) (必須)</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-bold">パスワード (確認用) (必須)</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            登録して始める
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="mb-0">アカウントを既にお持ちですか？ 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold">ログインはこちら</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection