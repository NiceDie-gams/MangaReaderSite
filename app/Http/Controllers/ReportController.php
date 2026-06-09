<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\NewReportSubmitted;
use App\Services\BannedWordChecker;
use App\Services\SettingsService;

class ReportController extends Controller
{
    public function makeReport(Request $request, SettingsService $settings): JsonResponse
    {
        if (!$settings->isReportsEnabled()) {
            return response()->json(['message' => 'Отправка жалоб временно отключена.'], 403);
        }
        $validated = $request->validate([
            'reportText' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'reportText.required' => 'Укажите текст жалобы.',
            'reportText.min' => 'Текст жалобы должен содержать не менее 10 символов.',
            'reportText.max' => 'Текст жалобы не должен превышать 2000 символов.',
        ]);

        if ($bannedWord = BannedWordChecker::getBannedWordInText($validated['reportText'])) {
            return response()->json([
                'message' => "Текст жалобы содержит запрещённое слово: {$bannedWord}"
            ], 422);
        }

        $report = Report::create([
            'user_id' => auth()->id(),
            'reportText' => $validated['reportText'],
            'isSolved' => false,
        ]);

        event(new NewReportSubmitted($report));

        return response()->json([
            'message' => 'Жалоба успешно отправлена.',
            'report' => $report,
        ], 201);
    }

    public function solveReport(Report $report): JsonResponse
    {
        if ($report->isSolved) {
            return response()->json([
                'message' => 'Жалоба уже отмечена как решённая.',
                'report' => $report,
            ]);
        }

        $report->update(['isSolved' => true]);

        return response()->json([
            'message' => 'Жалоба отмечена как решённая.',
            'report' => $report->fresh(),
        ]);
    }

    public function showReports(): JsonResponse
    {
        $reports = Report::query()
            ->with('user:id,name,email')
            ->latest()
            ->get();

        return response()->json($reports);
    }
}
