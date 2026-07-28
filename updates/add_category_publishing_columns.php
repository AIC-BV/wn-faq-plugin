<?php

namespace Aic\Faq\Updates;

use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Str;
use Schema;
use Db;

class AddCategoryPublishingColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('aic_faq_categories', 'sort_order')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->integer('sort_order')->default(1)->index();
            });
        }

        if (!Schema::hasColumn('aic_faq_categories', 'is_published')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->integer('is_published')->default(1);
            });
        }

        if (!Schema::hasColumn('aic_faq_categories', 'slug')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->string('slug', 100)->nullable()->index();
            });
        }

        // generate slugs and sort order for existing categories
        $categories = Db::table('aic_faq_categories')->whereNull('slug')->get();
        foreach ($categories as $category) {
            Db::table('aic_faq_categories')->where('id', $category->id)->update([
                'slug' => Str::slug($category->name),
                'sort_order' => $category->id,
            ]);
        }
    }

    public function down()
    {
        foreach (['sort_order', 'is_published', 'slug'] as $column) {
            if (Schema::hasColumn('aic_faq_categories', $column)) {
                Schema::table('aic_faq_categories', function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
