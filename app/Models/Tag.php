<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'attachment_id',
        'parent_id',
        'versus_excluded',
        'tag_manager_warning',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('name');
    }

    public function pulls()
    {
        return $this->belongsToMany(Pull::class)
            ->withPivot(['pull_id', 'tag_id']);
    }

    public function path()
    {
        return route('gallery.filter', [
            'filters' => 'tags:' . $this->slug,
        ]);
    }

    public function getTreeUpAttribute()
    {
        return collect(array_merge(
            [$this->id],
            $this->parent->treeUp ?? [null]
        ))->filter()->toArray();
    }

    public function getTreeDownAttribute()
    {
        return collect(array_merge(
            [$this->id],
            $this->children
                ->pluck('treeDown')
                ->flatten()
                ->toArray()
        ))->filter()->toArray();
    }

    public function checkChildren($ids)
    {
        foreach ($this->treeDown as $id) {
            if ($ids->contains($id)) {
                return true;
            }
        }

        return false;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();

        self::addGlobalScope('sort', function ($query) {
            $query->orderBy('name');
        });
    }
}
