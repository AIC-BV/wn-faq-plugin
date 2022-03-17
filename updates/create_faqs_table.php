<?php namespace Aic\Faq\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('aic_faq_faqs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->nullable()->index();

            $table->integer('is_published')->default(1)->index();
            $table->integer('is_featured')->default(0)->index();

            $table->string('question');
            $table->mediumText('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aic_faq_faqs');
    }
}
