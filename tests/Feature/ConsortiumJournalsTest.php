<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SecConsortiumSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsortiumJournalsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);
        $this->seed(SecConsortiumSeeder::class);
    }

    public function test_public_lists_five_sec_journals_and_consortium_officers(): void
    {
        $this->getJson('/api/v1/public/journals')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.name', 'SEC Journal of Software Engineering')
            ->assertJsonPath('data.4.name', 'SEC Journal of Management and Business Engineering');

        $this->getJson('/api/v1/public/journals/software-engineering')
            ->assertOk()
            ->assertJsonPath('data.slug', 'software-engineering')
            ->assertJsonStructure(['data' => ['editorial_board', 'recent_articles']]);

        $this->getJson('/api/v1/public/journals/software-engineering/editorial-board')
            ->assertOk()
            ->assertJsonPath('data.journal.slug', 'software-engineering');

        $this->getJson('/api/v1/public/journals/consortium-officers')
            ->assertOk()
            ->assertJsonCount(6, 'data')
            ->assertJsonPath('data.0.role_title', 'President');

        $this->getJson('/api/v1/public/org-info')
            ->assertOk()
            ->assertJsonPath('data.consortium.short_name', 'SEC')
            ->assertJsonCount(5, 'data.journals');
    }
}
