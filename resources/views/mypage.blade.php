@extends('app')

@section('title', 'マイページ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="my-4">マイページ</h1>
        </div>
    </div>
    
    <div class="d-flex mb-3">
        {{-- 新規投稿画面へのリンク (route('create') が新規投稿ルートと仮定) --}}
        <a href="{{ route('create') }}" class="btn btn-success mb-3 me-3">新規投稿</a>
        
        {{-- ブログ一覧画面へのリンク (route('index') が一覧ルートと仮定) --}}
        <a href="{{ route('index') }}" class="ms-auto btn btn-outline-secondary">他の人の投稿</a>
    </div>

    {{-- ここに自分の投稿一覧を表示するためのループ処理を記述します --}}
    {{-- $blogs の変数名は、コントローラーで渡す変数名に合わせてください --}}
    @foreach ($blogs as $blog)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $blog->title }}</h5>
                {{-- マイページなので投稿者名は通常表示しません --}}
                <p class="card-text">{{ Str::limit($blog->body, 100) }}</p>
                <a href="{{ route('detail', $blog->id) }}" class="btn btn-primary btn-sm">詳細</a>
                {{-- 編集・削除ボタンは自分の投稿なのでここに配置 --}}
                <a href="{{ route('edit', $blog->id) }}" class="btn btn-secondary btn-sm">編集</a>
                {{-- 削除フォーム --}}
                <form action="{{ route('destroy', $blog->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </div>
            <div class="card-footer text-muted">
                {{ $blog->created_at->diffForHumans() }}
            </div>
        </div>
    @endforeach
    
    {{-- ページネーション (必要であれば) --}}
    {{-- <div class="mt-4">
        {{ $blogs->links() }}
    </div> --}}
</div>
@endsection