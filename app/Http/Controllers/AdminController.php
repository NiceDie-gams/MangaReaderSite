<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
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
}
