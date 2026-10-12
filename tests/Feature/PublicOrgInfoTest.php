<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicOrgInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_org_info_includes_sec_contact_and_journal_content(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $response = $this->getJson('/api/v1/public/org-info');

        $response->assertOk()
            ->assertJsonPath('data.bank.account_name', 'Software Engineering Consortium')
            ->assertJsonPath('data.fees.manuscript_review', 10000)
            ->assertJsonPath('data.journal.short_name', 'SEC Journals')
            ->assertJsonPath('data.journal.editor_in_chief_message.signatory_title', 'Software Engineering Consortium')
            ->assertJsonPath('data.payment_mode', 'bank_transfer')
            ->assertJsonPath('data.contact.address.line1', 'Room 1, PG Lab, JUPEB Building Upstairs')
            ->assertJsonPath('data.contact.email', 'info@secjournals.net')
            ->assertJsonCount(2, 'data.contact.phones');
    }

    public function test_configured_bank_settings_override_defaults_for_public_org_info(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        \App\Models\SystemSetting::query()->updateOrCreate(
            ['group_name' => 'payment', 'setting_key' => 'bank_name'],
            ['setting_value' => 'Zenith Bank', 'is_secret' => false],
        );
        \App\Models\SystemSetting::query()->updateOrCreate(
            ['group_name' => 'payment', 'setting_key' => 'account_name'],
            ['setting_value' => 'Software Engineering Consortium', 'is_secret' => false],
        );
        \App\Models\SystemSetting::query()->updateOrCreate(
            ['group_name' => 'payment', 'setting_key' => 'account_number'],
            ['setting_value' => '9876543210', 'is_secret' => false],
        );

        $this->getJson('/api/v1/public/org-info')
            ->assertOk()
            ->assertJsonPath('data.bank.account_number', '9876543210')
            ->assertJsonPath('data.bank.bank_name', 'Zenith Bank')
            ->assertJsonPath('data.bank.account_name', 'Software Engineering Consortium');
    }
}
