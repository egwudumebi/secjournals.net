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
                    'issn' => config('jocrams.issn'),
                    'motto' => config('jocrams.motto'),
                    'short_name' => config('sicama.journal.short_name'),
                    'full_name' => config('sicama.journal.full_name'),
                    'opening_statement' => config('jocrams.opening_statement'),
                    'about' => config('jocrams.about'),
                    'vision' => config('jocrams.vision'),
                    'mission' => config('jocrams.mission'),
                    'areas_of_interest' => config('jocrams.areas_of_interest'),
                    'what_we_publish' => config('jocrams.what_we_publish'),
                    'why_publish' => config('jocrams.why_publish'),
                    'author_guidelines' => config('jocrams.author_guidelines'),
                    'editorial_leadership' => config('jocrams.editorial_leadership'),
                    'editor_in_chief_message' => config('jocrams.editor_in_chief_message'),
                ],
            ],
        ]);
    }
}
