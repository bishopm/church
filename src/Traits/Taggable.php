<?php

namespace Bishopm\Church\Traits;

use Bishopm\Church\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function hasTag($tag): bool
    {
        return $this->tags()->get()->contains($tag);
    }

    public function scopeWithTag($query, $tag){
        return $query->withWhereHas('tags', function ($q) use ($tag) { $q->where('name', 'like', $tag); })->get();
    }

    public function scopeWithTagType($query, $tag, $type){
        // Need a join to taggables with type referenced
        return $query->withWhereHas('tags', function ($q) use ($tag) { $q->where('name', 'like', $tag); })->get();
    }

    public function hasAnyTag(...$tags): bool
    {
        foreach (Arr::flatten($tags) as $tag) {
            if ($this->hasTag($tag)) {
                return true;
            }
        }

        return false;
    }

    public function addTag(...$tag): void
    {
        foreach (Arr::flatten($tag) as $tag) {
            $this->tags()->attach($tag);
        }
    }

    public function removeTag(...$tag): void
    {
        foreach (Arr::flatten($tag) as $tag) {
            $this->tags()->detach($tag);
        }
    }
}