@extends('layouts.app')

@section('content')
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
@endsection