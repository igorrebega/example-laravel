<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class UserTaskProgress extends Model implements HasMedia
{
    use HasMediaTrait;

    const STATUS_WORK = 1;
    const STATUS_QUEUE = 2;
    const STATUS_CANCELED = 3;
    const STATUS_REVIEW = 4;
    const STATUS_APPROVED = 5;
    const STATUS_DECLINED_BY_MANAGER = 6;
    const STATUS_DECLINED = 7;

    public $table = 'user_task_progress';
    protected $guarded = [];

    protected $casts = [
        'result' => 'array',
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(1280);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getStatusAsText()
    {
        $statuses = [
            self::STATUS_WORK                => _('In work'),
            self::STATUS_QUEUE               => _('In queue'),
            self::STATUS_CANCELED            => _('Canceled'),
            self::STATUS_REVIEW              => _('On review'),
            self::STATUS_APPROVED            => _('Approved'),
            self::STATUS_DECLINED_BY_MANAGER => _('Declined by manager'),
            self::STATUS_DECLINED            => _('Declined'),
        ];

        return array_get($statuses, $this->status);
    }

    /**
     * @return bool
     */
    public function isOnReview()
    {
        return ($this->status === self::STATUS_REVIEW);
    }

    /**
     * @return bool
     */
    public function isDeclinedByManager()
    {
        return ($this->status === self::STATUS_DECLINED_BY_MANAGER);
    }
}