<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->string('google_books_id')->primary();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('publication_date')->nullable();
            $table->string('isbn');
            $table->string('genre')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
