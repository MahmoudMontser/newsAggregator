<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('provider')->nullable();  // Article title
            $table->string('title')->nullable();  // Article title
            $table->string('author')->nullable();  // Article author (nullable)
            $table->text('content')->nullable();  // Article content
            $table->string('source')->nullable();  // The source of the article (e.g., NewsAPI, OpenNews) (nullable)
            $table->text('source_url')->nullable();  // URL to the original article
            $table->string('published_at')->nullable(); // Published date of the article
            $table->string('category')->nullable();  // Category of the article
            $table->timestamps();  // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
