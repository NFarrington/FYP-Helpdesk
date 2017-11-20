<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TicketPost
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketPost whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $ticket
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 */
class TicketPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'attachment'];

    /**
     * The user who submitted the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The ticket the post is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
