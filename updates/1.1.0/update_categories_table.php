<?php

use Aic\Faq\Models\Categories;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    protected $migrationTable = 'aic_faq_categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn($this->migrationTable, 'slug')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->string('slug')->after('name')->nullable();
            });
        }

        if (!Schema::hasColumn($this->migrationTable, 'sort_order')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('sort_order')->after('slug')->default(1)->index();
            });
        }

        if (!Schema::hasColumn($this->migrationTable, 'is_published')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('is_published')->after('sort_order')->default(1);
            });
        }

        // Generate unique slugs and sort order for existing categories.
        $categories = Categories::query()->whereNull('slug')->orderBy('id')->get();
        $sortOrder = 1;
        foreach ($categories as $category) {
            $category->slugAttributes();
            $category->sort_order = $sortOrder++;
            $category->save();
        }

        if (!$this->hasIndex('slug')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->unique('slug');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['sort_order', 'is_published', 'slug'] as $column) {
            if (Schema::hasColumn($this->migrationTable, $column)) {
                Schema::table($this->migrationTable, function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Check whether the given column already has a schema index, so the migration can be retried safely.
     */
    protected function hasIndex(string $column): bool
    {
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($this->migrationTable);

        foreach ($indexes as $index) {
            if ($index->getColumns() === [$column]) {
                return true;
            }
        }

        return false;
    }
};
