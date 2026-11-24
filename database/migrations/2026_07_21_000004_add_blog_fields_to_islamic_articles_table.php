<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('islamic_articles', function (Blueprint $table) {
            if (! Schema::hasColumn('islamic_articles', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('author');
            }

            if (! Schema::hasColumn('islamic_articles', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('tags');
            }

            if (! Schema::hasColumn('islamic_articles', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        $columns = collect(['excerpt', 'meta_title', 'meta_description'])
            ->filter(fn (string $column) => Schema::hasColumn('islamic_articles', $column))
            ->all();

        if ($columns === []) {
            return;
        }

        Schema::table('islamic_articles', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
