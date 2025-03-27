<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    // protected $casts = [
    //     'variations' => 'array',
    // ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100);
        // ->height(100);
        $this->addMediaConversion('small')
            ->width(480);
        // ->height(400);
        $this->addMediaConversion('large')
            ->width(1200);
        // ->height(800);
    }

    // public function scopeForVendor(Builder $query): Builder
    // {
    //     return $query->where('vendor_id', auth()->id());
    // }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variationTypes(): HasMany
    {
        return $this->hasMHasMany(VariationType::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMHasMany(ProductVariation::class, 'product_id');
    }
}
