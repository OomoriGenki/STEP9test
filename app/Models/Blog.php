<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    // 💡 $fillable の定義 
    protected $fillable = [
        'title', 
        'content', 
        'image', 
        'user_id' 
    ];

    // 💡 ユーザーとのリレーションを定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💡 いいねとのリレーションを定義
    public function likes()
    {
        // 1つのブログに対して「いいね」は複数（多）
        // Blog は Like モデルに対して 'has many' の関係を持つ
        return $this->hasMany(Like::class);
    }

    // 💡 特定のユーザーがそのブログ投稿に「いいね」をしているかどうかを確認
    /**
     * 特定のユーザーが現在の投稿に対して「いいね」しているかを確認する
     * @param \App\Models\User $user 確認するユーザーのモデルインスタンス
     * @return bool
     */
    public function likedBy(User $user)
    {
        // 特定のユーザーが現在の投稿に対して「いいね」しているかを確認し、
        // 現在の投稿に関する「いいね」のリレーションを返す。
        // exists() は、結果が存在するかどうかを true/false で返す。
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * 💡 ログインユーザー以外のブログ投稿一覧を取得する
     * @param int $user_id ログインユーザーのID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOtherBlog($user_id)
    {
        // ブログテーブルのデータで $user_id がログインユーザーIDと異なるデータを取得
        return $this->where('user_id', '!=', $user_id)
                    ->with('user') 
                    ->orderBy('created_at', 'desc')
                    ->get();
    

        // 取得したブログを返却
        return $blogs;
    }
}