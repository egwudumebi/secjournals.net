<?php

namespace Database\Seeders;

use App\Models\ConsortiumJournal;
use App\Models\EditorialBoardMember;
use Illuminate\Database\Seeder;

class SecConsortiumSeeder extends Seeder
{
    public function run(): void
    {
        $journals = [];

        foreach (config('sec.journals', []) as $journal) {
            $journals[] = ConsortiumJournal::query()->updateOrCreate(
                ['slug' => $journal['slug']],
                [
                    'short_name' => $journal['short_name'],
                    'name' => $journal['name'],
                    'description' => $journal['description'],
                    'issn' => $journal['issn'] ?? null,
                    'sort_order' => $journal['sort_order'],
                    'is_active' => true,
                ],
            );
        }

        EditorialBoardMember::query()
            ->where('board_scope', 'consortium')
            ->delete();

        foreach (config('sec.consortium_officers', []) as $index => $officer) {
            EditorialBoardMember::query()->create([
                'consortium_journal_id' => null,
                'board_scope' => 'consortium',
                'name' => $officer['name'],
                'role_title' => $officer['role'],
                'affiliation' => 'Software Engineering Consortium',
                'bio' => null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $defaultRoles = [
            'Chief Editor',
            'Managing Editor',
            'Associate Editor',
            'Editorial Board Member',
        ];

        foreach ($journals as $journal) {
            $existing = EditorialBoardMember::query()
                ->where('consortium_journal_id', $journal->id)
                ->where('board_scope', 'journal')
                ->count();

            if ($existing > 0) {
                continue;
            }

            foreach ($defaultRoles as $index => $role) {
                EditorialBoardMember::query()->create([
                    'consortium_journal_id' => $journal->id,
                    'board_scope' => 'journal',
                    'name' => 'To be announced',
                    'role_title' => $role,
                    'affiliation' => $journal->name,
                    'bio' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
