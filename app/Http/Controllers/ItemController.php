<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示 (index)
     */
    public function index(Request $request)
    {
        // ★ 1. $userId をここで定義する ★
        $userId = Auth::id();

        $query = Item::with(['user', 'likes']);

        // ログインユーザーが出品した商品を除外
        if ($userId) {
            $query->where('user_id', '!=', $userId);
        }

        // 1. 検索キーワードによる絞り込み
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        // 2. カテゴリによる絞り込み (slugで検索)
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        
        // ★ 3. 価格による絞り込み (min_price, max_price) を追加 ★
        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }
        
        // 4. ソート条件の適用
        $sort = $request->input('sort', 'id_asc');
        
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
                $query->latest(); // created_at の降順
                break;
            case 'id_asc':
            default:
                // 'id_asc' が指定されたか、またはデフォルトの場合に適用
                $query->orderBy('id', 'asc'); // 商品番号（ID）の昇順
                break;
        }

        // 5. ページネーションとクエリパラメータ保持
        $items = $query->paginate(15)->appends($request->query());

        // $userId はここで定義済みなので削除
        // return view('items.index', compact('items', 'userId')); は問題なし
        return view('items.index', compact('items', 'userId'));
    }

    /**
     * 商品詳細を表示 (show)
     */
    public function show(Item $item)
    {
        // ... (省略: 変更なし)
        $item->load(['user', 'likes', 'category']);
        
        $userId = Auth::id();
        $isLiked = false;

        if ($userId) {
            $isLiked = $item->likes->contains('user_id', $userId);
        }

        return view('items.show', [
            'product' => $item, // $itemの内容を'product'という名前でビューに渡す
            'isLiked' => $isLiked,
        ]);
    }

    /**
     * 出品フォームを表示 (create)
     */
    public function create()
    {
        // ... (省略: 変更なし)
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * 新しい商品を出品 (store)
     */
    public function store(Request $request)
    {
        // ... (省略: 変更なし)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:100|max:9999999',
            'stock' => 'required|integer|min:1',
            'image_path' => 'required|image|max:2048',
            'category_id' => 'required|exists:categories,id', 
            'condition' => 'required|string',
        ]);

        $path = $request->file('image_path')->store('images/items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'condition' => $validated['condition'],
            'image_path' => $path,
        ]);

        return redirect()->route('items.show', $item)
                            ->with('success', '商品を新たに出品しました！');
    }

    /**
     * 商品編集フォームを表示 (edit)
     */
    public function edit(Item $item)
    {
        // ... (省略: 変更なし)
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
        // ... (省略: 変更なし)
        if (Auth::id() !== $item->user_id) {
            return redirect()->route('items.show', $item)->with('error', 'この商品の更新権限はありません。');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:100|max:9999999',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|string',
            'image_path' => 'nullable|image|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image_path')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image_path')->store('images/items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.show', $item)
                            ->with('success', '商品を更新しました。');
    }

    /**
     * 商品を削除 (destroy)
     */
    public function destroy(Item $item)
    {
        // ... (省略: 変更なし)
        if (Auth::id() !== $item->user_id) {
            return redirect()->route('items.show', $item)->with('error', 'この商品の削除権限はありません。');
        }

        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->route('items.index')
                            ->with('success', '商品を削除しました。');
    }
}