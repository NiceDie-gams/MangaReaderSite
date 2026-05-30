<?php

namespace App\Database;

use Illuminate\Support\Facades\DB;

class SyncPostgresSequences
{
    /** @var list<string> */
    public const SEEDED_TABLES = [
        'titles',
        'chapters',
        'users',
        'comments',
        'chapter_pages',
        'tags',
        'title_tag',
        'favorites',
        'comments_likes',
    ];

    /**
     * @param  list<string>|null  $tables
     */
    public static function run(?array $tables = null): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($tables ?? self::SEEDED_TABLES as $table) {
            if (! preg_match('/^[a-z][a-z0-9_]*$/', $table)) {
                continue;
            }

            $sequence = DB::selectOne(
                'SELECT pg_get_serial_sequence(?, ?) AS sequence',
                [$table, 'id']
            );

            if (! $sequence?->sequence) {
                continue;
            }

            DB::statement(
                'SELECT setval(?, COALESCE((SELECT MAX(id) FROM '.$table.'), 1), true)',
                [$sequence->sequence]
            );
        }
    }
}
