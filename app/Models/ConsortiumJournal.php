<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsortiumJournal extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'slug',
        'short_name',
        'name',
        'description',
        'issn',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function editorialBoardMembers(): HasMany
    {
        return $this->hasMany(EditorialBoardMember::class, 'consortium_journal_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function publishedArticles(): HasMany
    {
        return $this->hasMany(PublishedArticle::class, 'consortium_journal_id');
    }

    public function volumes(): HasMany
    {
        return $this->hasMany(JournalVolume::class, 'consortium_journal_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(JournalSubmission::class, 'consortium_journal_id');
    }

    /** @return array<string, mixed> */
    public function toPublicArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'short_name' => $this->short_name,
            'name' => $this->name,
            'description' => $this->description,
            'issn' => $this->issn,
            'sort_order' => $this->sort_order,
            'landing_url' => '/journals/'.$this->slug,
            'editorial_board_url' => '/journals/'.$this->slug.'/editorial-board',
            'articles_url' => '/journals/'.$this->slug.'/articles',
            'guidelines_url' => '/journals/'.$this->slug.'/author-guidelines',
        ];
    }
}
