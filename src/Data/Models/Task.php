<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Task extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

    const DESC_MEDIA = 'description';

    const TYPE_PHOTO_CHECK = 1;

    protected $dates = [
        'deleted_at',
        'active_to'
    ];

    protected $guarded = [];

    /**
     * @return array
     */
    public function taskTypes(): array
    {
        return [
            self::TYPE_PHOTO_CHECK => _('Photo check')
        ];
    }

    /**
     * @return string
     */
    public function getTypeInText(): string
    {
        return array_get($this->taskTypes(), $this->type, '');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
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
            ->width(1280)
            ->performOnCollections(self::DESC_MEDIA);
    }
}
