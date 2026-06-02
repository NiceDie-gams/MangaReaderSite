<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\ChapterPage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ChapterFileService
{
    public function processZip(UploadedFile $zipFile, Chapter $chapter): void
    {
        $extractPath = storage_path("app/temp/chapter_{$chapter->id}_" . uniqid());

        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFile->path()) !== true) {
            throw new \Exception('Не удалось открыть ZIP-архив.');
        }
        $zip->extractTo($extractPath);
        $zip->close();

        $imageFiles = $this->findImageFiles($extractPath);

        if (empty($imageFiles)) {
            $this->deleteDirectory($extractPath);
            throw new \Exception('В ZIP-архиве не найдено ни одного изображения (jpg, jpeg, png, gif, webp).');
        }

        $this->savePages($imageFiles, $chapter);

        $this->deleteDirectory($extractPath);
    }


    public function processMultipleFiles(array $images, Chapter $chapter): void
    {
        if (empty($images)) {
            throw new \Exception('Не передано ни одного изображения.');
        }

        usort($images, function ($a, $b) {
            return strnatcmp($a->getClientOriginalName(), $b->getClientOriginalName());
        });

        $this->savePages($images, $chapter);
    }


    private function findImageFiles(string $directory): array
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }
            $extension = strtolower($fileInfo->getExtension());
            if (in_array($extension, $allowedExtensions, true)) {
                $files[] = $fileInfo->getPathname();
            }
        }

        usort($files, function ($a, $b) {
            return strnatcmp(basename($a), basename($b));
        });

        return $files;
    }


    private function savePages(array $items, Chapter $chapter): void
    {
        $pageNumber = 1;
        foreach ($items as $item) {
            $imagePath = $this->storeImage($item, $chapter);
            ChapterPage::create([
                'chapter_id'  => $chapter->id,
                'page_number' => $pageNumber++,
                'image_path'  => $imagePath,
            ]);
        }
    }


    private function storeImage($file, Chapter $chapter): string
    {
        $directory = $this->getDirectoryPath($chapter);

        if ($file instanceof UploadedFile) {
            $relativePath = $file->store($directory, 'public');
            return '/storage/' . $relativePath;
        }

        if (is_string($file) && is_file($file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION)) ?: 'jpg';
            $filename = Str::uuid()->toString() . '.' . $extension;
            $relativePath = $directory . '/' . $filename;

            Storage::disk('public')->put($relativePath, file_get_contents($file));
            return '/storage/' . $relativePath;
        }

        throw new \InvalidArgumentException('Неподдерживаемый тип файла для сохранения изображения.');
    }


    private function getDirectoryPath(Chapter $chapter): string
    {
        $slug = $chapter->titleBelong->slug;
        $chapterNumber = $chapter->chapter_number;
        return "manga/{$slug}/{$chapterNumber}";
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
