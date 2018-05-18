<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class UserInfo extends Model implements HasMedia
{
    use HasMediaTrait;

    const AVATAR_MEDIA_COLLECTION = 'avatar';

    protected $guarded = [];

    public $table = 'user_info';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->performOnCollections(self::AVATAR_MEDIA_COLLECTION);
    }
}
