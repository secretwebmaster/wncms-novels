<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('novels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('status')->default('published'); // published | drafted | trashed 

            $table->string('external_thumbnail')->nullable();
            $table->string('slug')->unique();

            $table->string('title');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->string('remark')->nullable();
            $table->integer('order')->nullable();
            $table->tinyInteger('series_status')->default(0)->comment('0=ongoing,1=completed,2=paused,3=dropped,4=upcoming');
            $table->integer('word_count')->nullable();

            $table->string('password')->nullable();
            $table->decimal('price', 9, 3)->nullable();

            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_dmca')->default(false);

            $table->dateTime('published_at');
            $table->dateTime('expired_at')->nullable();

            $table->string('source')->nullable();
            $table->string('ref_id')->nullable();

            $table->string('author')->nullable();
            $table->unsignedInteger('chapter_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novels');
    }
};
