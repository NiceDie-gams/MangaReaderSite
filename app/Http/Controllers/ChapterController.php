<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ChapterController extends Controller
{
    public function show(Title $title, Chapter $chapter): View
    {
        abort_unless($chapter->title_id === $title->id, 404);
        $chapter->load('pages');
        $firstPage = $chapter->pages->first();

        if (!$chapter->isApproved()) {
            $user = auth()->user();
            $isOwner = $user && $user->id === $chapter->uploaded_by;
            $isAdmin = $user && $user->isAdmin();
            if (!$isOwner && !$isAdmin) {
                abort(403, 'Глава ещё не опубликована.');
            }
        }

        $title->load('chapters');
        $chapters = $title->chapters;
        $currentIndex = $chapters->search(fn($ch) => $ch->id === $chapter->id);
        $prevChapter = $currentIndex > 0 ? $chapters[$currentIndex - 1] : null;
        $nextChapter = ($currentIndex < $chapters->count() - 1) ? $chapters[$currentIndex + 1] : null;

        return view('chapter-show', compact('title', 'chapter', 'firstPage', 'prevChapter', 'nextChapter'));
    }

    public function page(Chapter $chapter, int $page, Request $request): JsonResponse
    {
        $chapter->load('pages');
        $availablePages = $chapter->pages->pluck('page_number')->values()->all();
        $current = $chapter->pages->firstWhere('page_number', $page);
        if (! $current) {
            Log::warning('Chapter page not found', [
                'chapter_id' => $chapter->id,
                'title_id' => $chapter->title_id,
                'requested_page' => $page,
                'available_pages' => $availablePages,
            ]);
            abort(404);
        }

        $pages = $chapter->pages->values();
        $currentIndex = $pages->search(fn ($p) => $p->id === $current->id);
        $direction = $request->query('direction');

        $resolved = $current;
        $resolvedIndex = $currentIndex;

        if (in_array($direction, ['next', 'prev'], true)) {
            $targetIndex = $direction === 'next' ? $currentIndex + 1 : $currentIndex - 1;
            abort_unless($pages->has($targetIndex), 404);
            $resolved = $pages[$targetIndex];
            $resolvedIndex = $targetIndex;
        }

        $chaptersQuery = Chapter::query()->where('title_id', $chapter->title_id);
        $prevChapter = (clone $chaptersQuery)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();
        $nextChapter = (clone $chaptersQuery)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        $titleSlug = $chapter->titleBelong?->slug;
        abort_unless($titleSlug, 404);

        Log::info('Chapter page resolved', [
            'chapter_id' => $chapter->id,
            'requested_page' => $page,
            'direction' => $direction,
            'resolved_page' => $resolved->page_number,
            'prev_page_number' => $resolvedIndex > 0 ? $pages[$resolvedIndex - 1]->page_number : null,
            'next_page_number' => $resolvedIndex < ($pages->count() - 1) ? $pages[$resolvedIndex + 1]->page_number : null,
        ]);

        return response()->json([
            'image_path' => $resolved->image_path,
            'page_number' => $resolved->page_number,
            'has_prev_page' => $resolvedIndex > 0,
            'has_next_page' => $resolvedIndex < ($pages->count() - 1),
            'prev_page_number' => $resolvedIndex > 0 ? $pages[$resolvedIndex - 1]->page_number : null,
            'next_page_number' => $resolvedIndex < ($pages->count() - 1) ? $pages[$resolvedIndex + 1]->page_number : null,
            'prev_chapter_id' => $prevChapter?->id,
            'next_chapter_id' => $nextChapter?->id,
            'title_slug' => $titleSlug,
            'chapter_number' => $chapter->chapter_number,
        ]);
    }
}
