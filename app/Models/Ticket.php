<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property int $user_id
 * @property string $summary
 * @property int $department_id
 * @property int $status_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Department $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TicketPost[] $posts
 * @property-read \App\Models\TicketStatus $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket closed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket open()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket withAgent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket withCustomer()
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'summary',
    ];

    /**
     * Department the ticket is currently assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Posts associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(TicketPost::class);
    }

    /**
     * Status associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    /**
     * The user who submitted the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include 'with agent' tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAgent($query)
    {
        return $query->whereHas('status', function ($query) {
            $query->withAgent();
        });
    }

    /**
     * Scope a query to only include 'with customer' tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCustomer($query)
    {
        return $query->whereHas('status', function ($query) {
            $query->withCustomer();
        });
    }

    /**
     * Scope a query to only include 'open' tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->whereHas('status', function ($query) {
            $query->open();
        });
    }

    /**
     * Scope a query to only include 'closed' tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->whereHas('status', function ($query) {
            $query->closed();
        });
    }
}
