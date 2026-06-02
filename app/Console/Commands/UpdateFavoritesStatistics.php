<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Tag;
use App\Models\TagFavoriteStatistic;
use Illuminate\Support\Facades\DB;

class UpdateFavoritesStatistics extends Command
{
    protected $signature = 'stats:update-favorites';
    protected $description = 'Пересчитать статистику избранного по тегам';

    public function handle()
    {
        $this->info('Начинаем пересчёт статистики...');

        $stats = DB::table('favorites')
            ->join('titles', 'favorites.title_id', '=', 'titles.id')
            ->join('title_tag', 'titles.id', '=', 'title_tag.title_id')
            ->select('title_tag.tag_id', DB::raw('COUNT(*) as favorites_count'))
            ->groupBy('title_tag.tag_id')
            ->get();

        $bar = $this->output->createProgressBar(count($stats));
        $bar->start();

        foreach ($stats as $row) {
            TagFavoriteStatistic::updateOrCreate(
                ['tag_id' => $row->tag_id],
                [
                    'favorites_count'    => $row->favorites_count,
                    'last_calculated_at' => now(),
                ]
            );
            $bar->advance();
        }

        $missingTags = Tag::whereDoesntHave('statistic')->get();
        foreach ($missingTags as $tag) {
            TagFavoriteStatistic::updateOrCreate(
                ['tag_id' => $tag->id],
                [
                    'favorites_count'    => 0,
                    'last_calculated_at' => now(),
                ]
            );
        }

        $bar->finish();
        $this->newLine();
        $this->info('Статистика успешно обновлена!');
    }
}
