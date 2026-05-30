<?php

namespace Database\Seeders;

use App\Database\SyncPostgresSequences;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TitlesTableSeeder::class,
            ChaptersTableSeeder::class,
            UsersTableSeeder::class,
            CommentsTableSeeder::class,
            ChapterPagesTableSeeder::class,
            SessionsTableSeeder::class,
            TagsTableSeeder::class,
            TitleTagTableSeeder::class,
            FavoritesTableSeeder::class,
        ]);

        SyncPostgresSequences::run();
    }
}
