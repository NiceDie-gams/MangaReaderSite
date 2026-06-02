<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Chapter;

class TitleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Title::query()->with('tags');
        $search = $request->string('search')->toString();

        $tagsInput = $request->input('tags', []);
        $includeIds = [];
        $excludeIds = [];
        foreach ($tagsInput as $tagId => $value) {
            if ($value === 'include') {
                $includeIds[] = (int) $tagId;
            } elseif ($value === 'exclude') {
                $excludeIds[] = (int) $tagId;
            }
        }

        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        foreach ($includeIds as $tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        if (!empty($excludeIds)) {
            $query->whereDoesntHave('tags', fn ($q) => $q->whereIn('tags.id', $excludeIds));
        }

        $titles = $query->latest()->paginate(12)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($titles);
        }

        return view('home', [
            'titles'       => $titles,
            'tags'         => Tag::orderBy('name')->get(),
            'selectedTags' => $tagsInput,
            'search'       => $search,
        ]);
    }

    public function show(Title $title): View
    {
       $chaptersQuery = $title->chapters()->orderBy('chapter_number');

        if (!auth()->check() || (!auth()->user()->isTranslator() && !auth()->user()->isAdmin())) {
            $chaptersQuery->where('status', Chapter::STATUS_APPROVED);
        }

        $chapters = $chaptersQuery->get();
        $title->setRelation('chapters', $chapters);

        $title->load(['tags', 'comments.user']);
        $isFavorite = auth()->check() && auth()->user()->favoriteTitles()->where('title_id', $title->id)->exists();

        return view('title-show', compact('title', 'isFavorite'));
    }
}
