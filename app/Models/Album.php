<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Album extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = ['name'];

    public function getImageAttribute()
    {
        $images = $this->getMedia('image');
        $images->each(function ($item) {
            $item->url = $item->getUrl();
        });
        return $images;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image');
    }


}
