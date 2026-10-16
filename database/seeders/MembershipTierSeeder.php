<?php

namespace Database\Seeders;

use App\Models\MembershipTier;
use Illuminate\Database\Seeder;

class MembershipTierSeeder extends Seeder
{
    public function run(): void
    {
        // Flat SEC association membership: ₦25,000 (₦10k registration + ₦15k annual dues).
        $registrationFee = (float) config('sec.fees.membership_registration', 25000);

        MembershipTier::query()->updateOrCreate(
            ['slug' => 'sec'],
            [
                'name' => 'SEC Member',
                'description' => 'Official SEC association membership. Includes ID card, certificate, and welcome letter after payment.',
                'annual_dues' => $registrationFee,
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

        // Legacy tiers — retired from public signup (journal access is separate).
        foreach ([
            'full' => 'Full Member',
            'associate' => 'Associate Member',
            'sicama' => 'SICAMA Member',
        ] as $slug => $name) {
            MembershipTier::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => 'Legacy tier (retired). Use SEC Member.',
                    'annual_dues' => $registrationFee,
                    'currency' => 'NGN',
                    'benefits' => ['Member portal access'],
                    'sort_order' => 10,
                    'is_active' => false,
                    'min_documents_required' => 0,
                    'requires_approval' => true,
                ],
            );
        }
    }
}
