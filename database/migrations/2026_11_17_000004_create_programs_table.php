<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('cover_path')->nullable();
            $table->decimal('target_amount', 15, 2)->nullable();
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
