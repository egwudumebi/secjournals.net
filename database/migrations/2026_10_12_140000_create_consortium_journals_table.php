<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consortium_journals', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('short_name');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('issn')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('editorial_board_members', function (Blueprint $table): void {
            $table->foreignId('consortium_journal_id')
                ->nullable()
                ->after('id')
                ->constrained('consortium_journals')
                ->nullOnDelete();
            $table->string('board_scope')->default('journal')->after('consortium_journal_id');
        });

        Schema::table('published_articles', function (Blueprint $table): void {
            $table->foreignId('consortium_journal_id')
                ->nullable()
                ->after('id')
                ->constrained('consortium_journals')
                ->nullOnDelete();
        });

        Schema::table('journal_volumes', function (Blueprint $table): void {
            $table->foreignId('consortium_journal_id')
                ->nullable()
                ->after('id')
                ->constrained('consortium_journals')
                ->nullOnDelete();
        });

        Schema::table('journal_submissions', function (Blueprint $table): void {
            $table->foreignId('consortium_journal_id')
                ->nullable()
                ->after('id')
                ->constrained('consortium_journals')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('journal_submissions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('consortium_journal_id');
        });

        Schema::table('journal_volumes', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('consortium_journal_id');
        });

        Schema::table('published_articles', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('consortium_journal_id');
        });

        Schema::table('editorial_board_members', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('consortium_journal_id');
            $table->dropColumn('board_scope');
        });

        Schema::dropIfExists('consortium_journals');
    }
};
