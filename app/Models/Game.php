<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Game extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title','description','price','rental_price','stock'];

    //spatie media configuration

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->registerMediaConversions(function(media $media){
                $this->addMediaConversion('thumb')
                     ->width(200)
                     ->height(200);
             });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
