<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('novel_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained('novels')->onDelete('cascade');
            $table->unsignedInteger('number')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // multi-author support
            $table->string('status')->default('published'); // published | drafted | trashed 
            $table->string('slug')->unique();

            $table->string('title')->nullable();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('remark')->nullable();
            $table->integer('order')->nullable();

            $table->string('password')->nullable();
            $table->decimal('price', 9, 3)->nullable();

            $table->dateTime('published_at');
            $table->dateTime('expired_at')->nullable();

            $table->string('source')->nullable();
            $table->string('ref_id')->nullable();

            $table->string('author')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_chapters');
    }
};
