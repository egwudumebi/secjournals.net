<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\ConsortiumJournal;
use App\Models\EditorialBoardMember;
use App\Models\PublishedArticle;
use App\Support\Journal\PublishedArticlePresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsortiumJournalController extends Controller
{
    public function index(): JsonResponse
    {
        $journals = ConsortiumJournal::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (ConsortiumJournal $journal) => $journal->toPublicArray())
            ->values();

        return response()->json(['data' => $journals]);
    }

    public function show(string $slug): JsonResponse
    {
        $journal = ConsortiumJournal::query()->active()->where('slug', $slug)->firstOrFail();

        $board = EditorialBoardMember::query()
            ->where('consortium_journal_id', $journal->id)
            ->where('board_scope', 'journal')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['uuid', 'name', 'role_title', 'affiliation', 'bio', 'sort_order']);

        $articles = PublishedArticle::query()
            ->published()
            ->where('consortium_journal_id', $journal->id)
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->limit(12)
            ->get()
            ->map(fn (PublishedArticle $article) => PublishedArticlePresenter::toArray($article))
            ->values();

        return response()->json([
            'data' => [
                ...$journal->toPublicArray(),
                'editorial_board' => $board,
                'recent_articles' => $articles,
            ],
        ]);
    }

    public function articles(Request $request, string $slug): JsonResponse
    {
        $journal = ConsortiumJournal::query()->active()->where('slug', $slug)->firstOrFail();

        $paginator = PublishedArticle::query()
            ->published()
            ->where('consortium_journal_id', $journal->id)
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->paginate((int) $request->integer('per_page', 12));

        return response()->json([
            'data' => collect($paginator->items())->map(
                fn (PublishedArticle $article) => PublishedArticlePresenter::toArray($article)
            )->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'journal' => $journal->toPublicArray(),
        ]);
    }

    public function editorialBoard(string $slug): JsonResponse
    {
        $journal = ConsortiumJournal::query()->active()->where('slug', $slug)->firstOrFail();

        $board = EditorialBoardMember::query()
            ->where('consortium_journal_id', $journal->id)
            ->where('board_scope', 'journal')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['uuid', 'name', 'role_title', 'affiliation', 'bio', 'sort_order']);

        return response()->json([
            'data' => [
                'journal' => $journal->toPublicArray(),
                'members' => $board,
            ],
        ]);
    }

    public function consortiumOfficers(): JsonResponse
    {
        $officers = EditorialBoardMember::query()
            ->where('board_scope', 'consortium')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['uuid', 'name', 'role_title', 'affiliation', 'bio', 'sort_order']);

        return response()->json(['data' => $officers]);
    }
}
