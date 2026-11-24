<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('masjids', function (Blueprint $table) {
            if (! Schema::hasColumn('masjids', 'parking')) {
                $table->boolean('parking')->default(false);
            }

            if (! Schema::hasColumn('masjids', 'wheelchair')) {
                $table->boolean('wheelchair')->default(false);
            }

            if (! Schema::hasColumn('masjids', 'ladies_prayer')) {
                $table->boolean('ladies_prayer')->default(false);
            }

            if (! Schema::hasColumn('masjids', 'construction_status')) {
                $table->string('construction_status')->default('Completed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = collect(['parking', 'wheelchair', 'ladies_prayer', 'construction_status'])
            ->filter(fn (string $column) => Schema::hasColumn('masjids', $column))
            ->all();

        if ($columns === []) {
            return;
        }

        Schema::table('masjids', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
