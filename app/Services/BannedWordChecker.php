<?php

namespace App\Services;

use App\Models\BannedWord;
use Illuminate\Support\Facades\Cache;

class BannedWordChecker
{
    public static function hasBannedWords(string $text): bool
    {
        return self::getBannedWordInText($text) !== null;
    }

    public static function getBannedWordInText(string $text): ?string
    {
        $words = self::getBannedWordsList();
        $lowerText = mb_strtolower($text);

        foreach ($words as $word) {
            $pattern = '/\b' . preg_quote(mb_strtolower($word), '/') . '\b/u';
            if (preg_match($pattern, $lowerText)) {
                return $word;
            }
        }

        return null;
    }

    private static function getBannedWordsList(): array
    {
        return Cache::remember('banned_words_list', 3600, function () {
            return BannedWord::pluck('word')->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('banned_words_list');
    }
}
