<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'bio',
        'vote_count',
        'page_views',
        'share_count',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'vote_count' => 'integer',
    ];

    /**
     * Auto-generate slug from name on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Candidate $candidate) {
            if (empty($candidate->slug)) {
                $candidate->slug = Str::slug($candidate->name) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the category this candidate belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get vote transactions for this candidate.
     */
    public function voteTransactions(): HasMany
    {
        return $this->hasMany(VoteTransaction::class);
    }

    /**
     * Get successful vote transactions.
     */
    public function successfulTransactions(): HasMany
    {
        return $this->hasMany(VoteTransaction::class)->where('payment_status', 'success');
    }

    /**
     * Scope to only active candidates.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the candidate's rank in their category.
     */
    public function getRankInCategoryAttribute(): int
    {
        return self::where('category_id', $this->category_id)
            ->where('vote_count', '>', $this->vote_count)
            ->count() + 1;
    }

    /**
     * Get image URL with fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-candidate.png');
    }

    /**
     * Increment votes atomically and safely.
     */
    public function addVotes(int $votes): void
    {
        $this->increment('vote_count', $votes);
    }

    /**
     * Increment page views atomically.
     */
    public function incrementPageViews(): void
    {
        $this->increment('page_views');
    }

    /**
     * Increment share count atomically.
     */
    public function incrementShares(): void
    {
        $this->increment('share_count');
    }

    /**
     * Get total candidates in this candidate's category.
     */
    public function getTotalCandidatesInCategoryAttribute(): int
    {
        return self::where('category_id', $this->category_id)->active()->count();
    }

    /**
     * Get the absolute image URL for OG tags.
     */
    public function getAbsoluteImageUrlAttribute(): string
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
        return url('images/default-candidate.png');
    }

    /**
     * Get the canonical campaign URL.
     */
    public function getCampaignUrlAttribute(): string
    {
        return route('candidates.show', $this);
    }
}
