<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    protected $migrationTable = 'aic_faq_faqs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->migrationTable, function ($table) {
            $table->boolean('is_featured')->default(false)->change();
        });

        if (!Schema::hasColumn($this->migrationTable, 'sort_order')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('sort_order')->after('answer')->default(1)->index();
            });
        }

        // Generate sort order for every existing FAQ group, including uncategorized FAQs.
        $categoryIds = \DB::table($this->migrationTable)
            ->select('category_id')
            ->distinct()
            ->get();
        foreach ($categoryIds as $category) {
            $sortOrder = 1;
            $faqsQuery = \DB::table($this->migrationTable);
            if ($category->category_id === null) {
                $faqsQuery->whereNull('category_id');
            } else {
                $faqsQuery->where('category_id', $category->category_id);
            }
            $faqs = $faqsQuery->orderBy('id')->get();
            foreach ($faqs as $faq) {
                \DB::table($this->migrationTable)->where('id', $faq->id)->update([
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->migrationTable, function ($table) {
            $table->integer('is_featured')->default(0)->change();
        });

        foreach (['sort_order'] as $column) {
            if (Schema::hasColumn($this->migrationTable, $column)) {
                Schema::table($this->migrationTable, function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
