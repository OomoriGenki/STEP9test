<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category; // カテゴリモデルを使用
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ファイル操作のため追加

class ItemController extends Controller
{
    /**
     * 商品一覧を表示 (index)
     * 検索、ソート、ページネーションに対応
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'likes']); // Eager LoadingでN+1問題を回避

        // 1. 検索キーワードによる絞り込み
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }
        
        // 2. カテゴリによる絞り込み (必要に応じて追加)
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 3. ソート条件の適用
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->latest(); // created_at の降順
                break;
        }

        // 4. ページネーションとクエリパラメータ保持
        $items = $query->paginate(15)->appends($request->query());
        $userId = Auth::id();

        return view('items.index', compact('items', 'userId'));
    }

    /**
     * 商品詳細を表示 (show)
     */
    public function show(Item $item)
    {
        // Itemと関連情報（出品者、いいね情報、カテゴリ）をロード
        $item->load(['user', 'likes', 'category']);
        
        $userId = Auth::id();
        $isLiked = false;

        if ($userId) {
            // ログインユーザーがいいねしているか判定
            $isLiked = $item->likes->contains('user_id', $userId);
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    /**
     * 出品フォームを表示 (create)
     */
    public function create()
    {
        // カテゴリ一覧を取得し、フォームに渡す
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * 新しい商品を出品 (store)
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id', // 存在するカテゴリIDかチェック
            'price' => 'required|integer|min:100|max:9999999',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|string',
            'image' => 'required|image|max:2048', // 必須、画像ファイル、最大2MB
        ]);

        // 2. 画像ファイルの保存
        // storage/app/public/images/items フォルダに保存
        $path = $request->file('image')->store('images/items', 'public');

        // 3. データベースに保存
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'condition' => $validated['condition'],
            'image_path' => $path, // 公開パスを保存
        ]);

        return redirect()->route('items.show', $item)
                         ->with('success', '商品を新たに出品しました！');
    }

    /**
     * 商品編集フォームを表示 (edit)
     * Policyを使用して、出品者本人のみアクセスできるようにするのが望ましい
     */
    public function edit(Item $item)
    {
        // ユーザーがこの商品の出品者であることを確認
        if (Auth::id() !== $item->user_id) {
            return redirect()->route('items.show', $item)->with('error', 'この商品の編集権限はありません。');
        }

        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * 商品を更新 (update)
     */
    public function update(Request $request, Item $item)
    {
        // ユーザーがこの商品の出品者であることを確認
        if (Auth::id() !== $item->user_id) {
            return redirect()->route('items.show', $item)->with('error', 'この商品の更新権限はありません。');
        }

        // 1. バリデーション (画像は任意)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:100|max:9999999',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|string',
            'image' => 'nullable|image|max:2048', // nullを許容
        ]);

        $data = $validated;

        // 2. 画像がアップロードされた場合、古い画像を削除し、新しい画像を保存
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            // 新しい画像を保存
            $data['image_path'] = $request->file('image')->store('images/items', 'public');
        }

        // 3. データベースを更新
        $item->update($data);

        return redirect()->route('items.show', $item)
                         ->with('success', '商品を更新しました。');
    }

    /**
     * 商品を削除 (destroy)
     */
    public function destroy(Item $item)
    {
        // ユーザーがこの商品の出品者であることを確認
        if (Auth::id() !== $item->user_id) {
            return redirect()->route('items.show', $item)->with('error', 'この商品の削除権限はありません。');
        }

        // 1. 画像ファイルをストレージから削除
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        // 2. データベースから商品を削除
        $item->delete();

        return redirect()->route('items.index')
                         ->with('success', '商品を削除しました。');
    }
}