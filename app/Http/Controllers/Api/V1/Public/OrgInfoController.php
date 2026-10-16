<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\ConsortiumJournal;
use App\Support\Org\OrgPaymentDetails;
use Illuminate\Http\JsonResponse;

class OrgInfoController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $journals = ConsortiumJournal::query()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ConsortiumJournal $journal) => $journal->toPublicArray())
            ->values();

        return response()->json([
            'data' => [
                ...OrgPaymentDetails::publicPayload(),
                'consortium' => [
                    'short_name' => config('sec.parent_org.short_name'),
                    'full_name' => config('sec.parent_org.full_name'),
                    'motto' => config('sec.parent_org.motto'),
                    'description' => config('sec.parent_org.description'),
                    'officers' => config('sec.consortium_officers'),
                ],
                'journals' => $journals,
                'journal' => [
                    'issn' => null,
                    'motto' => config('sec.motto'),
                    'short_name' => config('sec.site.short_name'),
                    'full_name' => config('sec.site.full_name'),
                    'opening_statement' => config('sec.opening_statement'),
                    'about' => config('sec.about'),
                    'vision' => config('sec.vision'),
                    'mission' => config('sec.mission'),
                    'areas_of_interest' => config('sec.areas_of_interest'),
                    'what_we_publish' => config('sec.what_we_publish'),
                    'why_publish' => config('sec.why_publish'),
                    'author_guidelines' => config('sec.author_guidelines'),
                    'editorial_leadership' => [],
                    'editor_in_chief_message' => [
                        'title' => 'Welcome to SEC Journals',
                        'body' => "The Software Engineering Consortium provides a multi-journal platform for rigorous engineering scholarship.\n\nEach SEC journal maintains its own editorial leadership while sharing consortium standards for peer review and publication.",
                        'signatory_name' => 'Editorial Office',
                        'signatory_title' => 'Software Engineering Consortium',
                    ],
                ],
            ],
        ]);
    }
}
