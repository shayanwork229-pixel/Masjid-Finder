<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('islamic_articles', function (Blueprint $table) {
            if (! Schema::hasColumn('islamic_articles', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }

            if (! Schema::hasColumn('islamic_articles', 'reading_time')) {
                $table->unsignedInteger('reading_time')->default(5)->after('content');
            }

            if (! Schema::hasColumn('islamic_articles', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('reading_time');
            }

            if (! Schema::hasColumn('islamic_articles', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published');
            }

            if (! Schema::hasColumn('islamic_articles', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('is_featured');
            }

            if (! Schema::hasColumn('islamic_articles', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_trending');
            }

            if (! Schema::hasColumn('islamic_articles', 'tags')) {
                $table->string('tags')->nullable()->after('published_at');
            }
        });
    }

    public function down(): void
    {
        $columns = collect([
            'slug', 'reading_time', 'views', 'is_featured', 'is_trending', 'published_at', 'tags',
        ])->filter(fn (string $column) => Schema::hasColumn('islamic_articles', $column))->all();

        if ($columns === []) {
            return;
        }

        Schema::table('islamic_articles', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
