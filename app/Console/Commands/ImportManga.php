<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Tag;
use App\Models\Title;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportManga extends Command
{
    protected $signature = 'import:manga {--fresh : Truncate existing titles, chapters, pages and tags}';
    protected $description = 'Import manga from local directory into database';

    public function handle(): int
    {
        $mangaPath = env('MANGA_DIRECTORY');
        $tagsAllPath = env('TAGS_ALL_FILE');

        if (!$mangaPath || !is_dir($mangaPath)) {
            $this->error('MANGA_DIRECTORY not set or not a directory');
            return 1;
        }

        if (!file_exists($tagsAllPath)) {
            $this->error('TAGS_ALL_FILE not found');
            return 1;
        }

        if ($this->option('fresh')) {
            ChapterPage::truncate();
            Chapter::truncate();
            \DB::table('title_tag')->truncate();
            Title::truncate();
            Tag::truncate();
            $this->warn('Truncated existing manga data.');
        }


        $allTagNames = $this->parseTagsFile($tagsAllPath);
        $tagIds = [];
        foreach ($allTagNames as $name) {
            $tag = Tag::firstOrCreate(['name' => $name]);
            $tagIds[$name] = $tag->id;
        }
        $this->info('Synchronized global tags: ' . count($tagIds));


        $directories = glob($mangaPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $dir) {
            $folderName = basename($dir);
            $slug = Str::slug($folderName);


            $coverFiles = glob($dir . '/title.*');
            $coverPath = null;
            if (!empty($coverFiles)) {
                $coverPath = $this->storeFile($coverFiles[0], 'covers/' . $slug);
            }


            $title = Title::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $folderName,
                    'cover_image' => $coverPath,
                    'description' => null,
                ]
            );


            $tagsFile = $dir . '/tags.txt';
            if (file_exists($tagsFile)) {
                $titleTagNames = $this->parseTagsFile($tagsFile);
                $titleTagIds = [];
                foreach ($titleTagNames as $name) {
                    if (!isset($tagIds[$name])) {
                        $tag = Tag::firstOrCreate(['name' => $name]);
                        $tagIds[$name] = $tag->id;
                    }
                    $titleTagIds[] = $tagIds[$name];
                }
                $title->tags()->sync($titleTagIds);
            }


            $chapterDirs = glob($dir . '/*', GLOB_ONLYDIR);
            foreach ($chapterDirs as $chapterDir) {
                $chapterNumber = basename($chapterDir);
                if (!is_numeric($chapterNumber)) continue;

                $chapter = Chapter::updateOrCreate(
                    [
                        'title_id' => $title->id,
                        'chapter_number' => (int)$chapterNumber,
                    ],
                    ['title' => null]
                );


                $pageFiles = glob($chapterDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
                sort($pageFiles, SORT_NATURAL);
                $pageNumber = 1;
                foreach ($pageFiles as $pageFile) {
                    $pagePath = $this->storeFile(
                        $pageFile,
                        'manga/' . $slug . '/' . $chapterNumber
                    );
                    ChapterPage::updateOrCreate(
                        [
                            'chapter_id' => $chapter->id,
                            'page_number' => $pageNumber,
                        ],
                        ['image_path' => $pagePath]
                    );
                    $pageNumber++;
                }
            }

            $this->info("Imported: $folderName");
        }

        $this->info('Import completed.');
        return 0;
    }


    private function parseTagsFile(string $path): array
    {
        if (!file_exists($path)) return [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $tags = [];
        foreach ($lines as $line) {
            $parts = array_map('trim', explode(',', $line));
            foreach ($parts as $tag) {
                $normalized = mb_strtolower($tag);
                if ($normalized !== '') {
                    $tags[] = $normalized;
                }
            }
        }
        return array_values(array_unique($tags));
    }


    private function storeFile(string $sourcePath, string $destinationFolder): string
    {
        $filename = basename($sourcePath);
        $storagePath = $destinationFolder . '/' . $filename;
        Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));
        return '/storage/' . $storagePath;
    }
}
