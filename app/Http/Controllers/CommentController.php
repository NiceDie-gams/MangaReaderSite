<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLikes;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\CommentRepository;

class CommentController extends Controller
{

    private $commentRepository;

    public function __construct(CommentRepository $_commentRepository){
        $this->commentRepository = $_commentRepository;
    }

    public function index(Title $title): JsonResponse
    {
        $comments = $this->commentRepository->getCommentsForTitle($title);
        return response()->json($comments);
    }

    public function store(Request $request): JsonResponse
    {
        $comment = $this->commentRepository->createComment($request);

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
