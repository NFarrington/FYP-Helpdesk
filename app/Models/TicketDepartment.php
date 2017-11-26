<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TicketDepartment
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $internal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment external()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment internal()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment whereInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TicketDepartment whereName($value)
 * @mixin \Eloquent
 */
class TicketDepartment extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'internal' => 'bool',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Tickets currently assigned to the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'department_id');
    }

    /**
     * Scope a query to only include internal departments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInternal($query)
    {
        return $query->where('internal', 1);
    }

    /**
     * Scope a query to only include external departments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExternal($query)
    {
        return $query->where('internal', 0);
    }
}
