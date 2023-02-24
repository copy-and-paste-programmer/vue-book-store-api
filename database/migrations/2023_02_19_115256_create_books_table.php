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
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->longText('description');
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->string('publisher')->default(null);
            $table->date('published_at');
            $table->integer('star1_count')->default(0);
            $table->integer('star2_count')->default(0);
            $table->integer('star3_count')->default(0);
            $table->integer('star4_count')->default(0);
            $table->integer('star5_count')->default(0);
            $table->integer('average_rating')->default(0);
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
