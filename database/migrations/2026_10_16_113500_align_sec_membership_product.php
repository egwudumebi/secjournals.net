<?php

use App\Models\MembershipTier;
use App\Models\SystemSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $fee = (float) config('sec.fees.membership_registration', 25000);

        MembershipTier::query()->updateOrCreate(
            ['slug' => 'sec'],
            [
                'name' => 'SEC Member',
                'description' => 'Official SEC association membership. Includes ID card, certificate, and welcome letter after payment.',
                'annual_dues' => $fee,
                'currency' => 'NGN',
                'benefits' => [
                    'SEC membership ID card',
                    'Membership certificate',
                    'Official welcome letter',
                    'Association directory listing',
                    'Member portal access',
                    'Event privileges',
                ],
                'sort_order' => 0,
                'is_active' => true,
                'min_documents_required' => 0,
                'requires_approval' => false,
            ],
        );

        MembershipTier::query()->whereIn('slug', ['associate', 'full', 'sicama'])->update([
            'is_active' => false,
            'annual_dues' => $fee,
        ]);

        foreach (['fee_student_ngn', 'fee_professional_ngn'] as $key) {
            SystemSetting::query()->updateOrCreate(
                ['group_name' => 'membership', 'setting_key' => $key],
                ['setting_value' => (string) (int) $fee, 'is_secret' => false],
            );
        }
    }

    public function down(): void
    {
        MembershipTier::query()->where('slug', 'sec')->update(['is_active' => false]);
        MembershipTier::query()->whereIn('slug', ['associate', 'full'])->update([
            'is_active' => true,
            'annual_dues' => 20000,
        ]);
    }
};
