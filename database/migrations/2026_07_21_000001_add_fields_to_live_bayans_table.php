<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_bayans', function (Blueprint $table) {
            if (! Schema::hasColumn('live_bayans', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }

            if (! Schema::hasColumn('live_bayans', 'masjid_id')) {
                $table->unsignedBigInteger('masjid_id')->nullable()->after('scholar_name');
            }

            if (! Schema::hasColumn('live_bayans', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('youtube_url');
            }

            if (! Schema::hasColumn('live_bayans', 'audio_url')) {
                $table->string('audio_url')->nullable()->after('youtube_url');
            }

            if (! Schema::hasColumn('live_bayans', 'category')) {
                $table->string('category')->nullable()->after('description');
            }

            if (! Schema::hasColumn('live_bayans', 'duration')) {
                $table->string('duration')->nullable()->after('category');
            }

            if (! Schema::hasColumn('live_bayans', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('duration');
            }

            if (! Schema::hasColumn('live_bayans', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_live');
            }

            if (! Schema::hasColumn('live_bayans', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('is_featured');
            }

            if (! $this->hasForeignKey('live_bayans', 'live_bayans_masjid_id_foreign')) {
                $table->foreign('masjid_id')->references('id')->on('masjids')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        $columns = collect([
            'slug', 'masjid_id', 'thumbnail_path', 'audio_url',
            'category', 'duration', 'views', 'is_featured', 'is_trending',
        ])->filter(fn (string $column) => Schema::hasColumn('live_bayans', $column))->all();

        Schema::table('live_bayans', function (Blueprint $table) {
            if ($this->hasForeignKey('live_bayans', 'live_bayans_masjid_id_foreign')) {
                $table->dropForeign(['masjid_id']);
            }
        });

        if ($columns === []) {
            return;
        }

        Schema::table('live_bayans', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    private function hasForeignKey(string $table, string $foreignKey): bool
    {
        return collect(Schema::getForeignKeys($table))->contains(
            fn (array $item) => ($item['name'] ?? null) === $foreignKey
        );
    }
};
