<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Void_;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class VariationTypeOption extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $timestamps = false;


    public function registerMediaConversions(?Media $media = null): Void
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
}
