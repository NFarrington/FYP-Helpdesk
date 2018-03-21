<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role agent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereKey($value)
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'name', 'description',
    ];

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
        return $this->belongsToMany(Permission::class)->orderBy('key');
    }

    /**
     * Users with this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->orderBy('id');
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

    /**
     * Set the role's key.
     *
     * @param $value
     * @return void
     */
    public function setKeyAttribute($value)
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/\s+/', '.', $value);

        $this->attributes['key'] = $value;
    }
}
