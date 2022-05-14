<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use AngryMoustache\Rambo\Facades\Rambo;
use App\Enums\PullOriginEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Pull extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'pull_origin',
        'attachment_id',
        'youtube_id',
        'source',
        'online',
        'archived_at',
    ];

    public function path()
    {
        return route('gallery.show', $this);
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Attachment::class, 'variants');
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class)
            ->whereNull('parent_id');
    }

    public function allArtists()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function relatedTags()
    {
        return $this->tags()
            ->doesntHave('children')
            ->whereIn('tags.id', $this->tags->pluck('id'));
    }

    public function childlessTags()
    {
        return $this->belongsToMany(Tag::class)->doesntHave('children');
    }

    public function versusWinnerHistory()
    {
        return $this->hasMany(VersusHistory::class, 'winner_id');
    }

    public function versusLoserHistory()
    {
        return $this->hasMany(VersusHistory::class, 'loser_id');
    }

    public function getHistoryAttribute()
    {
        return $this->versusLoserHistory
            ->merge($this->versusWinnerHistory)
            ->sortByDesc('created_at');
    }

    public function getArtistListAttribute()
    {
        return $this->allArtists
            ->map(fn ($artist) => $artist->parent ?? $artist)
            ->unique();
    }

    public function getRelatedAttribute()
    {
        return Pull::whereHas('relatedTags')
            ->withCount('relatedTags')
            ->where('id', '!=', $this->id)
            ->get()
            ->each(function ($pull) {
                $pull->currentArtist = $pull->artists->filter(fn ($artist) =>
                    $this->artists->pluck('id')->contains($artist->id)
                )->isEmpty() ? 0 : 1;
            })
            ->sortByDesc('currentArtist')
            ->sortByDesc('related_tags_count')
            ->take(4 * 3);
    }

    public function getOriginAttribute()
    {
        return PullOriginEnum::get($this->pull_origin);
    }

    public function getOriginTagStyleAttribute()
    {
        return PullOriginEnum::color($this->pull_origin);
    }

    public function getOriginTagAttribute()
    {
        return new HtmlString('<span class="tag" style="'
            . $this->originTagStyle . '">'
            . $this->origin . '</span>');

    }

    public function getSourceLinkAttribute()
    {
        return $this->source ?? optional($this->attachment)->path();
    }

    public function getShowVariantsAttribute()
    {
        return $this->variants->prepend($this->attachment);
    }

    public function getScoreAttribute()
    {
        return $this->versusWinnerHistory->flatten()->pluck('points_gained')->sum();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function syncTags()
    {
        $this->tags()->sync(
            $this->tags
                ->map(fn ($tag) => $tag->treeUp)
                ->flatten()
                ->unique()
        );
    }

    public function filterScore($filters)
    {
        $scores = collect();
        $tags = collect($filters['tags'] ?? [])->filter()->keys();
        $artist = $filters['artist'] ?? null;

        if ($tags->isNotEmpty() || $artist) {
            if ($tags->isNotEmpty()) {
                $scores->prepend(
                    $this->versusWinnerHistory()
                        ->where('category_type', Tag::class)
                        ->whereIn('category_id', $tags->toArray())
                        ->get()
                );
            }

            if ($artist) {
                $scores->prepend(
                    $this->versusWinnerHistory()
                        ->where('category_type', Artist::class)
                        ->where('category_id', $artist)
                        ->get()
                );
            }

            $scores->prepend(
                $this->versusWinnerHistory()
                    ->where('category_type', null)
                    ->get()
            );
        } else {
            $scores->prepend($this->versusWinnerHistory);
        }

        $this->score = $scores->flatten()->pluck('points_gained')->sum();

        return $this;
    }

    public function scopeVersus($query)
    {
        return $query->whereHas('tags', function ($query) {
            return $query->withCount('pulls')->having('pulls_count', '>', 1);
        });
    }

    //
    // Filter scopes
    //

    public function scopeQuerySearch($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->where('name', 'LIKE', "%${value}%")
                ->orWhere('slug', 'LIKE', "%${value}%");
        });
    }

    public function scopeTagFilter($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            foreach (collect($value)->filter()->keys() as $tag) {
                $query->whereHas('tags', function ($query) use ($tag) {
                    return $query->where('tags.id', $tag);
                });
            }

            return $query;
        });
    }

    public function scopeArtistFilter($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('allArtists', function ($query) use ($value) {
                return $query->whereIn(
                    'artists.id',
                    Artist::find($value)->children->pluck('id')->merge($value)
                );
            });
        });
    }

    //
    // End filter scopes
    //

    public static function boot()
    {
        parent::boot();

        self::addGlobalScope('online', function ($query) {
            return $query->where('online', 1);
        });

        self::addGlobalScope('sorted', function ($query) {
            return $query->orderBy('pulls.updated_at', 'desc');
        });
    }
}
