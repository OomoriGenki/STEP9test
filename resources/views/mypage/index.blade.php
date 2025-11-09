@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h1 class="text-center mb-4 fw-bold text-success">マイページ</h1>
        
        <!-- ユーザー情報の表示 (ログインユーザー) -->
        <div class="card shadow-lg border-0 mb-5">
            <div class="card-body d-flex flex-column flex-md-row align-items-center p-4">
                {{-- プロフィール画像のプレースホルダーまたは登録画像 --}}
                <img src="{{ Auth::user()->profile_image_url ?? 'https://placehold.co/100x100/38a169/ffffff?text=User' }}" 
                    alt="プロフィール画像" class="rounded-circle me-4 mb-3 mb-md-0" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #38a169;">
                <div>
                    <h2 class="h4 mb-0">{{ Auth::user()->name }}</h2>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                    {{-- ★ プロフィール編集ルートに修正 ★ --}}
                    <a href="{{ route('account.edit') }}" class="btn btn-outline-secondary btn-sm mt-2">プロフィールを編集</a>
                </div>
            </div>
        </div>
        
        <!-- タブ切り替え（出品商品 / いいね一覧 / 購入履歴） -->
        <ul class="nav nav-tabs nav-fill mb-4" id="mypageTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fs-6 fs-md-5" id="items-tab" data-bs-toggle="tab" data-bs-target="#my-items" type="button" role="tab" aria-controls="my-items" aria-selected="true">
                    出品中の商品 ({{ $items->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fs-6 fs-md-5" id="likes-tab" data-bs-toggle="tab" data-bs-target="#liked-items" type="button" role="tab" aria-controls="liked-items" aria-selected="false">
                    いいねした商品 ({{ $likedItems->count() }})
                </button>
            </li>
            {{-- ★ 購入履歴タブの追加 ★ --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link fs-6 fs-md-5" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchased-items" type="button" role="tab" aria-controls="purchased-items" aria-selected="false">
                    購入履歴 ({{ $purchasedItems->count() }})
                </button>
            </li>
        </ul>

        <!-- タブコンテンツ -->
        <div class="tab-content" id="mypageTabContent">
            
            <!-- 1. 出品中の商品一覧 -->
            <div class="tab-pane fade show active" id="my-items" role="tabpanel" aria-labelledby="items-tab">
                @if ($items->isEmpty())
                    <div class="alert alert-warning text-center p-4 rounded-3">
                        <p class="mb-0 fs-5">現在、出品中の商品はありません。</p>
                        <a href="{{ route('items.create') }}" class="btn btn-success btn-lg mt-3 shadow-sm">今すぐ出品を始める</a>
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($items as $item)
                            <div class="col">
                                {{-- 商品カードのコンポーネントをインクルード --}}
                                @include('components.item_card', ['item' => $item]) 
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 2. いいねした商品一覧 -->
            <div class="tab-pane fade" id="liked-items" role="tabpanel" aria-labelledby="likes-tab">
                @if ($likedItems->isEmpty())
                    <div class="alert alert-info text-center p-4 rounded-3">
                        <p class="mb-0 fs-5">いいねした商品がまだありません。</p>
                        <a href="{{ route('items.index') }}" class="btn btn-primary btn-lg mt-3 shadow-sm">商品を探す</a>
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($likedItems as $item)
                            <div class="col">
                                {{-- 商品カードのコンポーネントをインクルード --}}
                                @include('components.item_card', ['item' => $item])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            {{-- ★ 3. 購入履歴一覧の追加 ★ --}}
            <div class="tab-pane fade" id="purchased-items" role="tabpanel" aria-labelledby="purchases-tab">
                @if ($purchasedItems->isEmpty())
                    <div class="alert alert-success text-center p-4 rounded-3">
                        <p class="mb-0 fs-5">まだ購入した取引がありません。</p>
                        <a href="{{ route('items.index') }}" class="btn btn-success btn-lg mt-3 shadow-sm">最初の商品を購入する</a>
                    </div>
                @else
                    <div class="row row-cols-1 g-4">
                        @foreach ($purchasedItems as $purchase)
                            {{-- $purchase は Purchase モデルのインスタンス --}}
                            <div class="col">
                                <div class="card shadow-sm border-start border-3 
                                    @if($purchase->status === 'shipped') border-success @else border-warning @endif 
                                    h-100">
                                    <div class="card-body d-flex flex-column flex-md-row align-items-center">
                                        {{-- 商品画像 --}}
                                        <div class="flex-shrink-0 me-md-4 mb-3 mb-md-0">
                                            <a href="{{ route('items.show', $purchase->item) }}">
                                                <img src="{{ Storage::url($purchase->item->image_path) }}" 
                                                    alt="{{ $purchase->item->name }}" 
                                                    class="rounded" 
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            </a>
                                        </div>
                                        
                                        {{-- 詳細情報 --}}
                                        <div class="flex-grow-1 text-center text-md-start">
                                            <h5 class="card-title mb-1 fw-bold">
                                                <a href="{{ route('items.show', $purchase->item) }}" class="text-decoration-none text-dark">
                                                    {{ $purchase->item->name }}
                                                </a>
                                            </h5>
                                            <p class="card-text mb-1 text-muted small">
                                                購入日時: {{ $purchase->created_at->format('Y/m/d H:i') }}
                                            </p>
                                            <p class="card-text mb-0 fs-5 text-danger fw-bold">
                                                購入価格: &yen;{{ number_format($purchase->price) }}
                                            </p>
                                        </div>

                                        {{-- ステータス --}}
                                        <div class="flex-shrink-0 mt-3 mt-md-0 text-md-end">
                                            <span class="badge 
                                                @if($purchase->status === 'shipped') bg-success 
                                                @elseif($purchase->status === 'pending_payment') bg-warning text-dark
                                                @else bg-secondary @endif 
                                                fs-6 py-2 px-3">
                                                {{ $purchase->status === 'shipped' ? '発送済み' : 
                                                   ($purchase->status === 'pending_payment' ? '支払い待ち' : '取引中') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection