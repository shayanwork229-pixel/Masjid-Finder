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
            if (! $this->hasIndex('masjids', 'masjids_is_featured_index')) {
                $table->index('is_featured');
            }

            if (! $this->hasIndex('masjids', 'masjids_sect_index')) {
                $table->index('sect');
            }

            if (! $this->hasIndex('masjids', 'masjids_area_index')) {
                $table->index('area');
            }

            if (! $this->hasIndex('masjids', 'masjids_latitude_longitude_index')) {
                $table->index(['latitude', 'longitude']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masjids', function (Blueprint $table) {
            if ($this->hasIndex('masjids', 'masjids_is_featured_index')) {
                $table->dropIndex(['is_featured']);
            }

            if ($this->hasIndex('masjids', 'masjids_sect_index')) {
                $table->dropIndex(['sect']);
            }

            if ($this->hasIndex('masjids', 'masjids_area_index')) {
                $table->dropIndex(['area']);
            }

            if ($this->hasIndex('masjids', 'masjids_latitude_longitude_index')) {
                $table->dropIndex(['latitude', 'longitude']);
            }
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(Schema::getIndexes($table))->contains(
            fn (array $item) => ($item['name'] ?? null) === $index
        );
    }
};
