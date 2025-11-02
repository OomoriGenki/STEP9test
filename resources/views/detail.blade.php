@extends('app')

@section('title', 'ブログ詳細')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <script src="{{ asset('js/like.js') }}"></script>
<div class="container">
    <h1>ブログ詳細</h1>
    <div class="container">
        <h2>{{ $blog->title }}</h2>
        <p>投稿者: {{ $blog->user->name }}</p>
        <p>{{ $blog->content }}</p>
        @if($blog->image)
        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">
        @endif
        <p>{{ $blog->created_at->format('Y-m-d') }}</p>
    
        <!-- いいねボタン -->
        <div class="mb-3">
        <button id="like-btn" class="border-0 bg-transparent"
            data-blog-id="{{ $blog->id }}"
            @if($blog->likedBy(Auth::user())) style="color: red;"@endif>
            <i class="fas fa-heart"></i> </button>
        
        <span id="like-count">{{ $blog->likes()->count() }}</span>
    </div>

    <a href="{{ route('edit', $blog->id) }}" class="btn btn-primary">更新する</a>
    <a href="{{ route('index') }}" class="btn btn-secondary">一覧に戻る</a>
    
    <form action="{{ route('destroy', $blog->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除</button>
    </form>
</div>
@endsection