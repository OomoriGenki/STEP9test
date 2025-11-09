{{-- 
    商品カードコンポーネント
    使用方法: @include('components.item_card', ['item' => $item])
    必須変数: $item (App\Models\Itemのインスタンス)
--}}
<div class="card h-100 shadow-sm border-0">
    {{-- 商品詳細へのリンク --}}
    <a href="{{ route('items.show', $item) }}" class="d-block card-img-top-wrap">
        {{-- 画像表示（$item->image_path がない場合はプレースホルダーを使用） --}}
        <img src="{{ $item->image_path ?? 'https://placehold.co/400x400/5c6bc0/ffffff?text=Item' }}" 
             class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
    </a>
    
    <div class="card-body d-flex flex-column p-3">
        {{-- 商品名 --}}
        <h5 class="card-title text-truncate mb-1">
            <a href="{{ route('items.show', $item) }}" class="text-decoration-none text-dark fw-bold">{{ $item->name }}</a>
        </h5>
        {{-- 価格 --}}
        <p class="card-text text-danger fs-5 fw-bold mt-auto mb-1">&yen;{{ number_format($item->price) }}</p>
    </div>
    
    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pt-0 pb-3 px-3">
        {{-- いいねボタンとカウント --}}
        <div class="d-flex align-items-center">
            @auth
                {{-- いいね状態によってボタンを切り替える (このロジックは商品一覧ページのものと共通) --}}
                @if ($item->isLikedBy(Auth::user()))
                    <form action="{{ route('likes.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        {{-- 塗りつぶされたハート --}}
                        <button type="submit" class="btn btn-sm text-danger p-0 border-0 bg-transparent" title="いいねを取り消す">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C3.824-3.143 14.16.814 8 12.044c-6.16-11.23-2.176-15.187 1.824-10.73z"/></svg>
                        </button>
                    </form>
                @else
                    <form action="{{ route('likes.store', ['item' => $item->id]) }}" method="POST" class="d-inline">
                        @csrf
                        {{-- 枠線だけのハート --}}
                        <button type="submit" class="btn btn-sm text-secondary p-0 border-0 bg-transparent" title="いいねする">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.535c-.413 1.257-.02 2.536.723 3.642.743 1.106 1.745 2.348 2.923 3.639.754.836 1.527 1.628 2.222 2.373a2.636 2.636 0 0 0 3.822 0c.695-.745 1.468-1.537 2.222-2.373 1.178-1.291 2.18-2.533 2.923-3.642.743-1.106 1.136-2.385.723-3.642C13.486.878 10.4.28 8.717 2.011zm1.751 10.985a2.128 2.128 0 0 1-3.504 0c-.822-.888-1.584-1.768-2.364-2.732-1.076-1.3-1.89-2.73-2.148-4.341-.157-1.475.249-2.894 1.157-3.795C4.28 2.39 6.046 2.19 8 3.568c1.954-1.378 3.72-.98 4.773 1.056.908.907 1.314 2.326 1.157 3.795-.258 1.611-1.072 3.041-2.148 4.341-.78.964-1.542 1.844-2.364 2.732z"/></svg>
                        </button>
                    </form>
                @endif
            @endauth
            {{-- いいね数。`likes_count` がない場合（Eager Loading忘れなど）はリレーションからカウント --}}
            <span class="ms-1 text-muted small">{{ $item->likes_count ?? $item->likes->count() }}</span>
        </div>
        
        <span class="text-muted small">在庫: {{ $item->stock }}</span>
    </div>
</div>