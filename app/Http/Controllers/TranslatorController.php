<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Title;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ZipArchive;

class TranslatorController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:translator,admin');
    // }

    public function dashboard(): View
    {
        $userId = auth()->id();

        $stats = [
            'total'      => Chapter::where('uploaded_by', $userId)->count(),
            'pending'    => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_PENDING)->count(),
            'approved'   => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_APPROVED)->count(),
            'rejected'   => Chapter::where('uploaded_by', $userId)->where('status', Chapter::STATUS_REJECTED)->count(),
        ];

        return view('translator.dashboard', compact('stats'));
    }

    public function create(): View
    {
        $titles = Title::orderBy('title')->get();
        return view('translator.chapters.create', compact('titles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id'          => 'required|exists:titles,id',
            'chapter_number'    => 'required|integer|min:1',
            //'chapter_title'     => 'nullable|string|max:255',
            'upload_method'     => 'required|in:zip,files',
            'zip_file'          => 'required_if:upload_method,zip|file|mimes:zip|max:204800',
            'images'            => 'required_if:upload_method,files|array',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $images = $request->file('images', []);

        \Log::info("Process count images", ['count'=>count($images)]);

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
            //'title'          => $validated['chapter_title'] ?? null,
            'status'         => $status,
            'uploaded_by'    => auth()->id(),
        ]);

        try {
            if ($validated['upload_method'] === 'zip') {
                $this->processZip($request->file('zip_file'), $chapter);
            } else {
                if (empty($images)) {
                    throw new \Exception('Не выбраны изображения для загрузки.');
                }

                $this->processMultipleFiles($images, $chapter);
            }
        } catch (\Exception $e) {
            $chapter->delete();
            Log::error('Ошибка при загрузке главы: ' . $e->getMessage());
            return back()->withInput()->withErrors(['upload_method' => 'Не удалось обработать файлы: ' . $e->getMessage()]);
        }

        $message = $autoApprove
            ? 'Глава успешно добавлена и сразу опубликована.'
            : 'Глава отправлена на модерацию.';

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


    public function edit(Chapter $chapter)
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
            'title_id'          => 'required|exists:titles,id',
            'chapter_number'    => 'required|integer|min:1',
            //'chapter_title'     => 'nullable|string|max:255',
            'upload_method'     => 'required|in:zip,files',
            'zip_file'          => 'required_if:upload_method,zip|file|mimes:zip|max:204800',
            'images'            => 'required_if:upload_method,files|array',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $images = $request->file('images', []);

        \Log::info("Process count images", ['count'=>count($images)]);


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
            //'title'          => $validated['chapter_title'] ?? null,
            'status'         => Chapter::STATUS_PENDING,
            'reject_reason'  => null,
        ]);

        $chapter->pages()->delete();

        try {
            if ($validated['upload_method'] === 'zip') {
                $this->processZip($request->file('zip_file'), $chapter);
            } else {
                if (empty($images)) {
                    throw new \Exception('Не выбраны изображения для загрузки.');
                }

                $this->processMultipleFiles($images, $chapter);
            }
        } catch (\Exception $e) {
            $chapter->update(['status' => Chapter::STATUS_REJECTED]);
            Log::error('Ошибка при обновлении главы: ' . $e->getMessage());
            return back()->withInput()->withErrors(['upload_method' => 'Не удалось обработать файлы: ' . $e->getMessage()]);
        }

        return redirect()->route('translator.chapters.index')
            ->with('success', 'Глава исправлена и отправлена на повторную модерацию.');
    }

    private function processZip($zipFile, Chapter $chapter): void
    {
        $extractPath = storage_path("app/temp/chapter_{$chapter->id}_" . uniqid());
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFile) !== true) {
            throw new \Exception('Не удалось открыть ZIP-архив.');
        }
        $zip->extractTo($extractPath);
        $zip->close();


        $files = [];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($extractPath, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            $extension = strtolower($fileInfo->getExtension());
            if (in_array($extension, $allowedExt, true)) {
                $files[] = $fileInfo->getPathname();
            }
        }

        if (empty($files)) {
            throw new \Exception('В ZIP-архиве не найдено ни одного изображения.');
        }

        usort($files, function($a, $b) {
            return strnatcmp(basename($a), basename($b));
        });

        $pageNumber = 1;
        foreach ($files as $file) {
            $imagePath = $this->storeImage($file, $chapter);
            ChapterPage::create([
                'chapter_id'   => $chapter->id,
                'page_number'  => $pageNumber++,
                'image_path'   => $imagePath,
            ]);
        }

        $this->deleteDirectory($extractPath);
    }


    private function processMultipleFiles(array $images, Chapter $chapter): void
    {
        usort($images, function($a, $b) {
            return strnatcmp($a->getClientOriginalName(), $b->getClientOriginalName());
        });

        $pageNumber = 1;
        foreach ($images as $image) {
            $path = $this->storeImage($image, $chapter);
            ChapterPage::create([
                'chapter_id'   => $chapter->id,
                'page_number'  => $pageNumber++,
                'image_path'   => $path,
            ]);
        }
    }

    private function storeImage($file, Chapter $chapter): string
    {
        $title = $chapter->titleBelong;
        $slug = $title->slug;
        $chapterNum = $chapter->chapter_number;
        $directory = "manga/{$slug}/{$chapterNum}";

        if ($file instanceof UploadedFile) {
            $relativePath = $file->store($directory, 'public');
            return '/storage/' . $relativePath;
        }

        if (is_string($file) && is_file($file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION)) ?: 'jpg';
            $filename = Str::uuid()->toString() . '.' . $extension;
            $relativePath = "{$directory}/{$filename}";

            Storage::disk('public')->put($relativePath, file_get_contents($file));
            return '/storage/' . $relativePath;
        }

        throw new \InvalidArgumentException('Неподдерживаемый тип файла для сохранения изображения.');
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
