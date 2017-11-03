<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TicketStatus
 *
 * @property int $id
 * @property string $name
 * @property int $state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus whereState($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus closed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus open()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus withAgent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketStatus withCustomer()
 */
class TicketStatus extends Model
{
    /**
     * Constant representing the state indicating a ticket is with agents.
     *
     * @var string
     */
    const STATUS_AGENT = 1;

    /**
     * Constant representing the state indicating a ticket is with customer.
     *
     * @var string
     */
    const STATUS_CUSTOMER = 2;

    /**
     * Constant representing the state indicating a ticket is closed.
     *
     * @var string
     */
    const STATUS_CLOSED = 3;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The tickets that currently have this status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id');
    }

    /**
     * Scope a query to only include 'with agent' statuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAgent($query)
    {
        return $query->where('state', self::STATUS_AGENT);
    }

    /**
     * Scope a query to only include 'with customer' statuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCustomer($query)
    {
        return $query->where('state', self::STATUS_CUSTOMER);
    }

    /**
     * Scope a query to only include 'open' statuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('state', '!=', self::STATUS_CLOSED);
    }

    /**
     * Scope a query to only include 'closed' statuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('state', self::STATUS_CLOSED);
    }
}
