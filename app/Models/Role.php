<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role agent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    /**
     * The name of the administrator role.
     *
     * @var string
     */
    const ROLE_ADMIN = 'admin';

    /**
     * The name of the agent role.
     *
     * @var string
     */
    const ROLE_AGENT = 'agent';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Permissions this role has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Users with this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Returns the admin role.
     *
     * @return $this
     */
    public function scopeAdmin()
    {
        return $this->where('key', self::ROLE_ADMIN)->first();
    }

    /**
     * Returns the admin role.
     *
     * @return $this
     */
    public function scopeAgent()
    {
        return $this->where('key', self::ROLE_AGENT)->first();
    }
}
