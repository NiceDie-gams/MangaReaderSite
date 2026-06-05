<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Title;
use App\Services\ChapterFileService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Events\NewChapterUploaded;

class TranslatorController extends Controller
{
    protected ChapterFileService $fileService;

    public function __construct(ChapterFileService $fileService)
    {
        // $this->middleware('auth');
        // $this->middleware('role:translator,admin');
        $this->fileService = $fileService;
    }

    public function dashboard(): View
    {
        $userId = auth()->id();

        $stats = [
            'total'    => Chapter::where('uploaded_by', $userId)->count(),
            'pending'  => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_PENDING)->count(),
            'approved' => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_APPROVED)->count(),
            'rejected' => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_REJECTED)->count(),
        ];

        $recentChapters = Chapter::where('uploaded_by', $userId)
            ->with('titleBelong')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('translator.dashboard', compact('stats', 'recentChapters'));
    }

    public function create(): View
    {
        $titles = Title::orderBy('title')->get();
        return view('translator.chapters.create', compact('titles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id'       => 'required|exists:titles,id',
            'chapter_number' => 'required|integer|min:1',
            'upload_method'  => 'required|in:zip,files',
            'zip_file'       => 'required_if:upload_method,zip|file|mimes:zip|max:204800',
            'images'         => 'required_if:upload_method,files|array|min:1',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $exists = Chapter::where('title_id', $validated['title_id'])
            ->where('chapter_number', $validated['chapter_number'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['chapter_number' => 'Глава с таким номером уже существует (включая черновики и главы на модерации).']);
        }

        $autoApprove = config('app.auto_approve_translations', false);
        $status = $autoApprove ? Chapter::STATUS_APPROVED : Chapter::STATUS_PENDING;

        $chapter = Chapter::create([
            'title_id'       => $validated['title_id'],
            'chapter_number' => $validated['chapter_number'],
            'status'         => $status,
            'uploaded_by'    => auth()->id(),
        ]);

        try {
            if ($validated['upload_method'] === 'zip') {
                $this->fileService->processZip($request->file('zip_file'), $chapter);
            } else {
                $images = $request->file('images');
                if (!is_array($images) || count($images) === 0) {
                    throw new \Exception('Не выбраны изображения для загрузки.');
                }
                $this->fileService->processMultipleFiles($images, $chapter);
            }
        } catch (\Exception $e) {
            $chapter->delete();
            return back()
                ->withInput()
                ->withErrors(['upload_method' => 'Не удалось обработать файлы: ' . $e->getMessage()]);
        }

        $message = $autoApprove
            ? 'Глава успешно добавлена и сразу опубликована.'
            : 'Глава отправлена на модерацию.';


        event(new NewChapterUploaded($chapter));

        return redirect()->route('translator.chapters.index')->with('success', $message);
    }

    public function index(Request $request): View
    {
        $userId = auth()->id();
        $query = Chapter::where('uploaded_by', $userId)
            ->with('titleBelong')
            ->orderBy('created_at', 'desc');

        if ($status = $request->get('status')) {
            if (in_array($status, [Chapter::STATUS_PENDING, Chapter::STATUS_APPROVED, Chapter::STATUS_REJECTED])) {
                $query->where('status', $status);
            }
        }

        $chapters = $query->paginate(20)->withQueryString();
        return view('translator.chapters.index', compact('chapters'));
    }

    public function edit(Chapter $chapter): View
    {
        if ($chapter->uploaded_by !== auth()->id() || !$chapter->isRejected()) {
            abort(403, 'Редактирование доступно только для отклонённых глав, загруженных вами.');
        }

        $titles = Title::orderBy('title')->get();
        $chapter->load('pages');
        return view('translator.chapters.edit', compact('chapter', 'titles'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        if ($chapter->uploaded_by !== auth()->id() || !$chapter->isRejected()) {
            abort(403);
        }

        $validated = $request->validate([
            'title_id'       => 'required|exists:titles,id',
            'chapter_number' => 'required|integer|min:1',
            'upload_method'  => 'required|in:zip,files',
            'zip_file'       => 'required_if:upload_method,zip|file|mimes:zip|max:204800',
            'images'         => 'required_if:upload_method,files|array|min:1',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $exists = Chapter::where('title_id', $validated['title_id'])
            ->where('chapter_number', $validated['chapter_number'])
            ->where('id', '!=', $chapter->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['chapter_number' => 'Глава с таким номером уже существует.']);
        }

        $chapter->update([
            'title_id'       => $validated['title_id'],
            'chapter_number' => $validated['chapter_number'],
            'status'         => Chapter::STATUS_PENDING,
            'reject_reason'  => null,
        ]);

        $chapter->pages()->delete();

        try {
            if ($validated['upload_method'] === 'zip') {
                $this->fileService->processZip($request->file('zip_file'), $chapter);
            } else {
                $images = $request->file('images');
                if (!is_array($images) || count($images) === 0) {
                    throw new \Exception('Не выбраны изображения для загрузки.');
                }
                $this->fileService->processMultipleFiles($images, $chapter);
            }
        } catch (\Exception $e) {
            $chapter->update(['status' => Chapter::STATUS_REJECTED]);
            return back()
                ->withInput()
                ->withErrors(['upload_method' => 'Не удалось обработать файлы: ' . $e->getMessage()]);
        }

        return redirect()->route('translator.chapters.index')
            ->with('success', 'Глава исправлена и отправлена на повторную модерацию.');
    }
}
