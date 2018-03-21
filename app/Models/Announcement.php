<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereUserId($value)
 * @mixin \Eloquent
 */
class Announcement extends Model
{
    /**
     * The representation of an unpublished announcement.
     *
     * @var string
     */
    const STATUS_UNPUBLISHED = 0;

    /**
     * The representation of a published announcement.
     *
     * @var string
     */
    const STATUS_PUBLISHED = 1;

    /**
     * The representation of a published and active announcement.
     *
     * @var string
     */
    const STATUS_ACTIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'status'];

    /**
     * Original user who created the announcement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active announcements.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->orderByDesc('created_at');
    }

    /**
     * Scope a query to only include published announcements.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->whereIn('status', [self::STATUS_PUBLISHED, self::STATUS_ACTIVE]);
    }

    /**
     * Checks whether the announcement has been published to normal users.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Checks whether the announcement has been published to normal users.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED
            || $this->status === self::STATUS_ACTIVE;
    }

    /**
     * The string representation of the current status.
     *
     * @return string
     */
    public function getStatusString()
    {
        switch ($this->status) {
            case self::STATUS_UNPUBLISHED:
                return 'Unpublished';
            case self::STATUS_PUBLISHED:
                return 'Published';
            case self::STATUS_ACTIVE:
                return 'Active';
        }
    }
}
