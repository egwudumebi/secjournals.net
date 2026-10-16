<?php

namespace Database\Seeders;

use App\Enums\JournalIssueStatus;
use App\Enums\JournalVolumeStatus;
use App\Enums\PublishedArticleStatus;
use App\Models\ConsortiumJournal;
use App\Models\JournalIssue;
use App\Models\JournalVolume;
use App\Models\PublishedArticle;
use App\Services\Journal\PublishedArticleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CrossrefSampleArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $journal = ConsortiumJournal::query()->where('slug', 'software-engineering')->first()
            ?? ConsortiumJournal::query()->orderBy('sort_order')->first();

        $volume = JournalVolume::query()->firstOrCreate(
            [
                'consortium_journal_id' => $journal?->id,
                'volume_number' => 1,
                'year' => 2026,
            ],
            [
                'title' => 'Volume 1',
                'description' => 'Inaugural volume of SEC Journals.',
                'status' => JournalVolumeStatus::Published,
                'sort_order' => 1,
                'published_at' => now(),
            ],
        );

        $issue = JournalIssue::query()->firstOrCreate(
            [
                'journal_volume_id' => $volume->id,
                'issue_number' => 1,
            ],
            [
                'title' => 'Issue 1',
                'description' => 'Demonstration sample issue demonstrating SEC article landing pages for Crossref membership review.',
                'status' => JournalIssueStatus::Published,
                'is_current' => true,
                'sort_order' => 1,
                'published_at' => now(),
            ],
        );

        JournalIssue::query()
            ->where('id', '!=', $issue->id)
            ->where('is_current', true)
            ->update(['is_current' => false]);

        $issue->update([
            'status' => JournalIssueStatus::Published,
            'is_current' => true,
            'published_at' => $issue->published_at ?? now(),
        ]);

        $service = app(PublishedArticleService::class);
        $year = 2026;

        foreach ($this->samples() as $index => $sample) {
            $article = PublishedArticle::query()->updateOrCreate(
                ['slug' => $sample['slug']],
                [
                    'consortium_journal_id' => $journal?->id,
                    'title' => $sample['title'],
                    'authors' => $sample['authors'],
                    'abstract' => $sample['abstract'],
                    'keywords' => $sample['keywords'],
                    'article_type' => $sample['article_type'],
                    'received_at' => $sample['received_at'],
                    'revised_at' => $sample['revised_at'],
                    'accepted_at' => $sample['accepted_at'],
                    'published_at' => $sample['published_at'],
                    'journal_issue_id' => $issue->id,
                    'volume_number' => 1,
                    'issue_number' => 1,
                    'page_start' => $sample['page_start'],
                    'page_end' => $sample['page_end'],
                    'copyright' => "© {$year} SEC Journals / Software Engineering Consortium (SEC).",
                    'license' => 'Creative Commons Attribution 4.0 International (CC BY 4.0)',
                    'doi' => null,
                    'status' => PublishedArticleStatus::Published,
                    'is_crossref_sample' => true,
                    'sort_order' => $index + 1,
                ],
            );

            $article->citation = $service->buildCitation($article);
            $this->ensureSamplePdf($article);
            $article->save();
        }
    }

    private function ensureSamplePdf(PublishedArticle $article): void
    {
        if ($article->pdf_path && Storage::disk('local')->exists($article->pdf_path)) {
            return;
        }

        $html = view('articles.sample-pdf', [
            'article' => $article,
            'sample' => [],
        ])->render();

        $pdf = Pdf::loadHTML($html)->setPaper('a4');
        $path = 'published-articles/'.$article->uuid.'/'.$article->slug.'.pdf';
        Storage::disk('local')->put($path, $pdf->output());

        $article->pdf_path = $path;
        $article->pdf_original_filename = $article->slug.'.pdf';
    }

    /** @return list<array<string, mixed>> */
    private function samples(): array
    {
        return [
            [
                'slug' => 'agile-devops-practices-in-nigerian-software-teams',
                'title' => 'Agile and DevOps Practices in Nigerian Software Engineering Teams: An Empirical Study',
                'authors' => [
                    [
                        'name' => 'Chinedu A. Okonkwo',
                        'affiliation' => 'Department of Computer Science, Nnamdi Azikiwe University, Awka, Nigeria',
                        'orcid' => '0000-0002-7001-1001',
                    ],
                    [
                        'name' => 'Amaka N. Eze',
                        'affiliation' => 'Software Engineering Consortium Research Lab, Awka, Nigeria',
                        'orcid' => null,
                    ],
                ],
                'abstract' => 'This study investigates how agile and DevOps practices are adopted across software engineering teams in Nigeria. Using surveys and semi-structured interviews with practitioners from academia-linked startups and industry partners, the paper identifies adoption patterns, tooling choices, and organisational constraints. Results show that continuous integration improves delivery cadence, while limited automated testing and infrastructure access remain barriers. Recommendations are offered for training programmes and industry–academia collaboration under the Software Engineering Consortium.',
                'keywords' => 'agile, DevOps, software engineering, Nigeria, continuous integration',
                'article_type' => 'Research Article',
                'received_at' => '2025-11-10',
                'revised_at' => '2026-01-20',
                'accepted_at' => '2026-02-08',
                'published_at' => '2026-03-15',
                'page_start' => '1',
                'page_end' => '17',
            ],
            [
                'slug' => 'prompt-engineering-patterns-for-reliable-llm-applications',
                'title' => 'Prompt Engineering Patterns for Reliable Large Language Model Applications in Engineering Workflows',
                'authors' => [
                    [
                        'name' => 'Fatima B. Yusuf',
                        'affiliation' => 'Department of Computer Engineering, Ahmadu Bello University, Zaria, Nigeria',
                        'orcid' => '0000-0002-7002-2002',
                    ],
                    [
                        'name' => 'Ifeanyi O. Nwankwo',
                        'affiliation' => 'Centre for Artificial Intelligence and Prompt Engineering, SEC',
                        'orcid' => '0000-0002-7002-2003',
                    ],
                ],
                'abstract' => 'Large language models are increasingly embedded in engineering support tools, documentation assistants, and code review workflows. This article proposes a reusable set of prompt engineering patterns that improve reliability, reduce hallucination risk, and support auditability in professional settings. Case studies from software and systems engineering tasks demonstrate measurable gains in answer consistency when structured prompts, validation loops, and domain constraints are combined.',
                'keywords' => 'prompt engineering, large language models, AI reliability, software workflows',
                'article_type' => 'Research Article',
                'received_at' => '2025-12-05',
                'revised_at' => '2026-02-12',
                'accepted_at' => '2026-03-01',
                'published_at' => '2026-03-15',
                'page_start' => '18',
                'page_end' => '36',
            ],
            [
                'slug' => 'iot-enabled-power-monitoring-for-campus-electrical-systems',
                'title' => 'IoT-Enabled Power Monitoring for Campus Electrical Systems: A Prototype Evaluation',
                'authors' => [
                    [
                        'name' => 'Samuel K. Boateng',
                        'affiliation' => 'Department of Electrical and Electronic Engineering, University of Ghana, Legon, Ghana',
                        'orcid' => '0000-0002-7003-3003',
                    ],
                    [
                        'name' => 'Ngozi C. Obi',
                        'affiliation' => 'Department of Electronic and Computer Engineering, Nnamdi Azikiwe University, Awka, Nigeria',
                        'orcid' => null,
                    ],
                    [
                        'name' => 'Hassan A. Ibrahim',
                        'affiliation' => 'Software Engineering Consortium, Industry Collaboration Unit',
                        'orcid' => '0000-0002-7003-3004',
                    ],
                ],
                'abstract' => 'This paper presents a prototype Internet-of-Things architecture for monitoring electrical load patterns on a university campus. Sensor nodes, edge gateways, and a lightweight analytics dashboard were evaluated for accuracy, latency, and deployment cost. Findings indicate that low-cost sensing can support energy awareness and maintenance planning when paired with clear data governance and engineering maintenance processes. Implications for electronic and electrical engineering education and facilities management are discussed.',
                'keywords' => 'IoT, power monitoring, electrical engineering, campus systems, energy management',
                'article_type' => 'Research Article',
                'received_at' => '2026-01-09',
                'revised_at' => null,
                'accepted_at' => '2026-03-02',
                'published_at' => '2026-03-15',
                'page_start' => '37',
                'page_end' => '54',
            ],
        ];
    }
}
