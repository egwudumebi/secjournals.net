<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->where('email', 'admin@secjournals.test')->value('id')
            ?? User::query()->where('email', 'admin@secjournals.test')->value('id');

        $this->ensureLogo();

        $settings = [
            ['group_name' => 'general', 'setting_key' => 'site_name', 'setting_value' => 'SEC Journals', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_tagline', 'setting_value' => 'Software Engineering Consortium Journals', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'journal_full_name', 'setting_value' => 'Software Engineering Consortium Journals', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'parent_org_name', 'setting_value' => 'SEC', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'parent_org_full_name', 'setting_value' => 'Software Engineering Consortium', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'parent_org_motto', 'setting_value' => 'Fusion of Academia and Industry to provide resources for productivity and advancement of the society.', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'parent_org_logo_path', 'setting_value' => 'branding/sec-logo-square.png', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_logo_path', 'setting_value' => 'branding/sec-logo-square.png', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'contact_email', 'setting_value' => 'info@secjournals.net', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'support_email', 'setting_value' => 'info@secjournals.net', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'social_facebook', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'social_linkedin', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'social_x', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'social_instagram', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'social_youtube', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'timezone', 'setting_value' => 'Africa/Lagos', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'language', 'setting_value' => 'en', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'app_name', 'setting_value' => 'SEC Journals', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_banner_enabled', 'setting_value' => '0', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_banner_message', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_banner_link', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_banner_link_label', 'setting_value' => 'Learn more', 'is_secret' => false],
            ['group_name' => 'general', 'setting_key' => 'site_banner_style', 'setting_value' => 'info', 'is_secret' => false],

            ['group_name' => 'notifications', 'setting_key' => 'email_notifications', 'setting_value' => '1', 'is_secret' => false],
            ['group_name' => 'notifications', 'setting_key' => 'new_member_alerts', 'setting_value' => '1', 'is_secret' => false],
            ['group_name' => 'notifications', 'setting_key' => 'payment_notifications', 'setting_value' => '1', 'is_secret' => false],
            ['group_name' => 'notifications', 'setting_key' => 'journal_submission_alerts', 'setting_value' => '1', 'is_secret' => false],
            ['group_name' => 'notifications', 'setting_key' => 'event_registration_alerts', 'setting_value' => '1', 'is_secret' => false],
            ['group_name' => 'notifications', 'setting_key' => 'system_updates', 'setting_value' => '0', 'is_secret' => false],

            ['group_name' => 'membership', 'setting_key' => 'auto_approval', 'setting_value' => '0', 'is_secret' => false],
            ['group_name' => 'membership', 'setting_key' => 'fee_student_ngn', 'setting_value' => '25000', 'is_secret' => false],
            ['group_name' => 'membership', 'setting_key' => 'fee_professional_ngn', 'setting_value' => '25000', 'is_secret' => false],
            ['group_name' => 'membership', 'setting_key' => 'fee_institutional_ngn', 'setting_value' => '150000', 'is_secret' => false],
            ['group_name' => 'membership', 'setting_key' => 'duration_months', 'setting_value' => '12', 'is_secret' => false],
            ['group_name' => 'membership', 'setting_key' => 'renewal_reminder_days', 'setting_value' => '30', 'is_secret' => false],

            ['group_name' => 'payment', 'setting_key' => 'currency', 'setting_value' => 'NGN', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'bank_name', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'account_name', 'setting_value' => 'Software Engineering Consortium', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'account_number', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'payment_gateway', 'setting_value' => 'bank_transfer', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'public_key', 'setting_value' => '', 'is_secret' => false],
            ['group_name' => 'payment', 'setting_key' => 'secret_key', 'setting_value' => '', 'is_secret' => true],

            ['group_name' => 'security', 'setting_key' => 'password_min_length', 'setting_value' => '12', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'password_expiry_days', 'setting_value' => '90', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'minimum_password_length', 'setting_value' => '8', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'require_special_characters', 'setting_value' => '0', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'require_numbers', 'setting_value' => '0', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'require_uppercase_letters', 'setting_value' => '0', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'session_timeout_minutes', 'setting_value' => '30', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'max_login_attempts', 'setting_value' => '5', 'is_secret' => false],
            ['group_name' => 'security', 'setting_key' => 'enable_two_factor_authentication', 'setting_value' => '0', 'is_secret' => false],

            ['group_name' => 'signatory', 'setting_key' => 'signatory_name', 'setting_value' => 'Executive Secretary', 'is_secret' => false],
            ['group_name' => 'signatory', 'setting_key' => 'signatory_title', 'setting_value' => 'Software Engineering Consortium', 'is_secret' => false],
            ['group_name' => 'signatory', 'setting_key' => 'signature_image_path', 'setting_value' => '', 'is_secret' => false],
        ];

        foreach ($settings as $setting) {
            SystemSetting::query()->updateOrCreate(
                [
                    'group_name' => $setting['group_name'],
                    'setting_key' => $setting['setting_key'],
                ],
                [
                    'setting_value' => $setting['setting_value'],
                    'is_secret' => $setting['is_secret'],
                    'updated_by' => $adminId,
                ],
            );
        }
    }

    private function ensureLogo(): void
    {
        $source = public_path('images/sec-logo.png');
        if (! File::exists($source)) {
            return;
        }

        Storage::disk('public')->makeDirectory('branding');
        Storage::disk('public')->put('branding/sec-logo.png', File::get($source));

        $square = public_path('images/sec-logo-square.png');
        if (File::exists($square)) {
            Storage::disk('public')->put('branding/sec-logo-square.png', File::get($square));
        }
    }
}
