<?php

namespace App\Http\Controllers;

use App\Models\ConsortiumJournal;
use App\Models\PublishedArticle;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = rtrim((string) config('app.url'), '/');

        $urls = [
            ['loc' => $base.'/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $base.'/sec', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base.'/journals', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base.'/articles', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base.'/articles/current-issue', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $base.'/news', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => $base.'/events', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => $base.'/contact', 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];

        foreach (ConsortiumJournal::query()->active()->orderBy('sort_order')->get() as $journal) {
            $urls[] = ['loc' => $base.$journal->toPublicArray()['landing_url'], 'changefreq' => 'weekly', 'priority' => '0.85'];
            $urls[] = ['loc' => $base.$journal->toPublicArray()['editorial_board_url'], 'changefreq' => 'monthly', 'priority' => '0.7'];
            $urls[] = ['loc' => $base.$journal->toPublicArray()['articles_url'], 'changefreq' => 'weekly', 'priority' => '0.8'];
        }

        foreach (PublishedArticle::query()->published()->orderByDesc('published_at')->get() as $article) {
            $urls[] = [
                'loc' => $base.$article->landingPath(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'lastmod' => optional($article->updated_at)?->toAtomString(),
            ];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
