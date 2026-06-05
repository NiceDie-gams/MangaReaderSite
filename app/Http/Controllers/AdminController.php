<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Report;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Tag;
use Illuminate\Support\Facades\Artisan;
use App\Repositories\TitleRepository;

class AdminController extends Controller
{
    private $titleRepository;

    public function __construct(TitleRepository $titleRepository)
    {
        $this->titleRepository = $titleRepository;
    }

    public function dashboard(): View
    {
        $stats = [
            'chapters_pending' => Chapter::where('status', Chapter::STATUS_PENDING)->count(),
            'chapters_approved' => Chapter::where('status', Chapter::STATUS_APPROVED)->count(),
            'chapters_rejected' => Chapter::where('status', Chapter::STATUS_REJECTED)->count(),
            'reports_open' => Report::where('isSolved', false)->count(),
            'users_total' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function chapters(): View
    {
        $chapters = Chapter::query()
            ->with(['titleBelong:id,title,slug', 'uploadedBy:id,name,email'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.chapters', compact('chapters'));
    }

    public function approveChapter(Chapter $chapter): RedirectResponse
    {
        $chapter->update([
            'status' => Chapter::STATUS_APPROVED,
            'reject_reason' => null,
        ]);

        return back()->with('success', "Глава #{$chapter->chapter_number} одобрена.");
    }

    public function rejectChapter(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'reject_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $chapter->update([
            'status' => Chapter::STATUS_REJECTED,
            'reject_reason' => $validated['reject_reason'],
        ]);

        return back()->with('success', "Глава #{$chapter->chapter_number} отклонена.");
    }

    public function reports(): View
    {
        $reports = Report::query()
            ->with('user:id,name,email,role')
            ->latest()
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function solveReport(Report $report): RedirectResponse
    {
        if (!$report->isSolved) {
            $report->update(['isSolved' => true]);
        }

        return back()->with('success', 'Жалоба отмечена как решённая.');
    }

    public function users(): View
    {
        $users = User::query()
            ->withCount(['uploadedChapters', 'comments', 'favorites'])
            ->latest()
            ->paginate(25);

        return view('admin.users', compact('users'));
    }

    public function statistics(): View
    {
        $tags = Tag::with('statistic')
            ->orderByDesc(
                Tag::select('favorites_count')
                    ->from('tag_favorites_statistics')
                    ->whereColumn('tag_favorites_statistics.tag_id', 'tags.id')
            )
            ->paginate(20);

        return view('admin.statistics', compact('tags'));
    }

    public function updateStatistics(Request $request)
    {
        Artisan::call('stats:update-favorites');
        $output = Artisan::output();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Статистика обновлена']);
        }

        return redirect()->back()->with('success', 'Статистика обновлена');
    }

    public function storeTitle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cover_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $cover = $request->file('cover_image');

        $title = $this->titleRepository->storeTitle($validated, $cover);

        return redirect()
            ->route('titles.show', $title)
            ->with('success', 'Манга успешно добавлена.');
    }

    public function deleteTitle(Title $title): RedirectResponse
    {
        $this->titleRepository->deleteTitle($title);

        return redirect()->back()->with('success', 'Манга успешно удалена.');
    }

    public function updateTitle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id'          => ['required', 'exists:titles,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['exists:tags,id'],
        ]);

        $title = Title::findOrFail($validated['id']);
        $title->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ]);

        $title->tags()->sync($request->tags ?? []);

        return redirect()->back()->with('success', 'Манга успешно обновлена.');
    }

    public function titles(): View
    {
        $titles = Title::with('tags')->latest()->paginate(15);
        $allTags = Tag::orderBy('name')->get();

        return view('admin.manga', compact('titles', 'allTags'));
    }
}
