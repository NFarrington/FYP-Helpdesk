<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property \Carbon\Carbon|null $visible_from
 * @property \Carbon\Carbon|null $visible_to
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereVisibleFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereVisibleTo($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'visible_from', 'visible_to'];

    protected $dates = ['visible_from', 'visible_to'];

    /**
     * Scope a query to only include published articles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('visible_from', '<=', Carbon::now())->where(function ($query) {
            $query->whereNull('visible_to')->orWhere('visible_to', '>', Carbon::now());
        });
    }

    /**
     * Check if an article is currently published (i.e. visible to general users).
     *
     * An article is considered published if:
     *      visible_from - is a date in the past
     *      visible_to   - is a date in the future, or null
     *
     * @return bool
     */
    public function isPublished()
    {
        $fromCheck = $this->visible_from !== null && $this->visible_from->isPast();
        $toCheck = $this->visible_to === null || $this->visible_to->isFuture();

        return $fromCheck && $toCheck;
    }
}
