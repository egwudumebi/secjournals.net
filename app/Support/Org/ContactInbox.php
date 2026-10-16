<?php

namespace App\Support\Org;

use App\Models\SystemSetting;

class ContactInbox
{
    /**
     * Official SEC Journals contact inbox from client docs / config.
     * Prefer admin settings when set; otherwise config('sec.site.email').
     */
    public static function email(): string
    {
        $fromSettings = SystemSetting::query()
            ->where('group_name', 'general')
            ->where('setting_key', 'contact_email')
            ->value('setting_value');

        if (is_string($fromSettings) && filter_var(trim($fromSettings), FILTER_VALIDATE_EMAIL)) {
            return trim($fromSettings);
        }

        return (string) config('sec.site.email', 'info@secjournals.net');
    }
}
