<?php

namespace Bishopm\Church\Traits;

use Bishopm\Church\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withPivot('taggable_type')->orderBy('name');
    }

    public function hasTag($tag): bool
    {
        return $this->tags()->get()->contains($tag);
    }

    public function scopeWithTag($query, $tag){
        return $query->withWhereHas('tags', function ($q) use ($tag) { $q->where('name', '=', $tag); });
    }

    public function scopeWithTags($query, $tags){
        $alltags=[];
        foreach ($tags as $tag){
            $alltags[]=$tag->name;
        }
        return $query->withWhereHas('tags', function ($q) use ($alltags) { $q->whereIn('name', $alltags); });
    }

    public function scopeWithTagType($query, $type){
        return $query->withWhereHas('tags', function ($q) use($type) { $q->where('taggable_type','=',$type);});
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