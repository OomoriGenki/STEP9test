@extends('layouts.app') 

@section('content')
    <div class="container">
        {{-- カード全体を中央寄せし、幅を制限 --}}
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg my-5">
                    
                    {{-- カードヘッダー --}}
                    <div class="card-header bg-white text-center border-bottom-0 pt-4">
                        <h4 class="mb-0">Register</h4>
                    </div>

                    {{-- カードボディ: フォームを配置 --}}
                    <div class="card-body pt-3">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            {{-- 1. Name (ユーザー名) フィールド --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Name (ユーザー名)</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 2. 名前 (漢字) フィールド --}}
                            <div class="mb-3">
                                <label for="first_name_kanji" class="form-label">名前 (漢字)</label>
                                <input id="first_name_kanji" type="text" class="form-control @error('first_name_kanji') is-invalid @enderror" 
                                    name="first_name_kanji" value="{{ old('first_name_kanji') }}" required>
                                @error('first_name_kanji')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- 3. 名前 (カナ) フィールド --}}
                            <div class="mb-3">
                                <label for="first_name_kana" class="form-label">名前 (カナ)</label>
                                <input id="first_name_kana" type="text" class="form-control @error('first_name_kana') is-invalid @enderror" 
                                    name="first_name_kana" value="{{ old('first_name_kana') }}" required>
                                @error('first_name_kana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 4. Email Address フィールド --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 5. Password フィールド --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 6. Confirm Password フィールド --}}
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" 
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>

                            {{-- Register ボタン --}}
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            
                            {{-- ログインリンク (画面遷移図 に従って、登録ボタン/戻るボタンでログイン画面に戻れるように) --}}
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    ログイン画面に戻る
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection