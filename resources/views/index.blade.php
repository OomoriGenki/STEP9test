@extends('app')

@section('title', 'ブログ一覧')

@section('content')
<div class="container">
    <h1>ブログ一覧</h1>
    
    <a href="{{ route('create') }}" class="btn btn-success mb-3">新規投稿</a>

    <form action="{{ route('search') }}" method="GET" class="my-3">
    <div class="row align-items-center">
        <div class="col-4">
            <input type="text" name="title" class="form-control" placeholder="タイトルで検索" value="{{ request('title') }}">
        </div>
        <div class="col-4">
            <input type="date" name="created_at" class="form-control" value="{{ request('created_at') }}">
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </div>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>投稿者</th>
                <th>タイトル</th>
                <th>内容</th>
                <th>画像</th>
                <th>投稿日</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($blogs as $blog)
                <tr>
        <td>{{ $blog->user->name }}</td>
        <td>{{ $blog->title }}</td>
        <td>{{ $blog->content }}</td>
        <td>
            @if ($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" width="100">
            @else
                画像なし
            @endif
        </td>
        <td>{{ $blog->created_at->format('Y-m-d') }}</td>
        <td><a href="{{ route('detail', $blog->id) }}" class="btn btn-primary">詳細</a>

            <form action="{{ route('destroy', $blog->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除</button>
            </form>

        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">該当する記事はありません。</td>
    </tr>
@endforelse