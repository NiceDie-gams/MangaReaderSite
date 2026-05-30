<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLikes;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Title $title): JsonResponse
    {
        $comments = $title->comments()
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

        return response()->json($comments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title_id' => ['required', 'exists:titles,id'],
            'content' => ['required', 'string', 'max:1500'],
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'title_id' => $validated['title_id'],
            'content' => $validated['content'],
        ])->load('user:id,name');

        return response()->json([
            'id' => $comment->id,
            'title_id' => $comment->title_id,
            'content' => $comment->content,
            'likes' => $comment->likes ?? 0,
            'created_at' => $comment->created_at,
            'user' => $comment->user,
            'liked_by_user' => false,
        ], 201);
    }

    public function like(Comment $comment): JsonResponse
    {
        $userId = auth()->id();

        $alreadyLiked = CommentLikes::query()
            ->where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyLiked) {
            return response()->json([
                'liked' => true,
                'likes' => $comment->likes ?? 0,
            ]);
        }

        DB::transaction(function () use ($comment, $userId) {
            CommentLikes::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
            ]);
            $comment->increment('likes');
        });

        return response()->json([
            'liked' => true,
            'likes' => $comment->fresh()->likes,
        ]);
    }

    public function unlike(Comment $comment): JsonResponse
    {
        $userId = auth()->id();

        $like = CommentLikes::query()
            ->where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if (!$like) {
            return response()->json([
                'liked' => false,
                'likes' => $comment->likes ?? 0,
            ]);
        }

        DB::transaction(function () use ($comment, $like) {
            $like->delete();
            if ($comment->likes > 0) {
                $comment->decrement('likes');
            }
        });

        return response()->json([
            'liked' => false,
            'likes' => max(0, $comment->fresh()->likes),
        ]);
    }
}
