<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('excerpt_en')->nullable()->after('excerpt');
            $table->longText('content_en')->nullable()->after('content');
            $table->string('meta_title_en')->nullable()->after('meta_description');
            $table->string('meta_description_en', 500)->nullable()->after('meta_title_en');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
            $table->longText('content_en')->nullable()->after('content');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->longText('content_en')->nullable()->after('content');
            $table->string('meta_title_en')->nullable()->after('meta_description');
            $table->string('meta_description_en', 500)->nullable()->after('meta_title_en');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('position_en')->nullable()->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('categories', fn (Blueprint $table) => $table->dropColumn('name_en'));
        Schema::table('posts', fn (Blueprint $table) => $table->dropColumn(['title_en', 'excerpt_en', 'content_en', 'meta_title_en', 'meta_description_en']));
        Schema::table('programs', fn (Blueprint $table) => $table->dropColumn(['title_en', 'description_en', 'content_en']));
        Schema::table('galleries', fn (Blueprint $table) => $table->dropColumn(['title_en', 'description_en']));
        Schema::table('pages', fn (Blueprint $table) => $table->dropColumn(['title_en', 'content_en', 'meta_title_en', 'meta_description_en']));
        Schema::table('team_members', fn (Blueprint $table) => $table->dropColumn('position_en'));
    }
};
