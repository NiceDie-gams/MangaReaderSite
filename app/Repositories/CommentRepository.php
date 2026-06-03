<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Title;
use Illuminate\Http\Request;

class CommentRepository
{
    public function getCommentsForTitle(Title $title)
    {
        return $title->comments()
            ->with('user:id,name')
            ->when(auth()->check(), function ($query) {
                $query->withExists([
                    'commentLikes as liked_by_user' => fn ($q) => $q->where('user_id', auth()->id()),
                ]);
            })
            ->get()
            ->map(fn (Comment $comment) => [
                'id' => $comment->id,
                'title_id' => $comment->title_id,
                'content' => $comment->content,
                'likes' => $comment->likes ?? 0,
                'created_at' => $comment->created_at,
                'user' => $comment->user,
                'liked_by_user' => (bool) ($comment->liked_by_user ?? false),
            ]);
    }

    public function createComment(Request $request): Comment
    {
        $validated = $request->validate([
            'title_id' => ['required', 'exists:titles,id'],
            'content' => ['required', 'string', 'max:1500'],
        ]);

        return Comment::create([
            'user_id' => auth()->id(),
            'title_id' => $validated['title_id'],
            'content' => $validated['content'],
        ])->load('user:id,name');
    }
}
