<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {

            $table->string('status')->default('pending')->after('title');


            $table->text('reject_reason')->nullable()->after('status');


            $table->foreignId('uploaded_by')
                  ->nullable()
                  ->after('reject_reason')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['status', 'reject_reason', 'uploaded_by']);
        });
    }
};
