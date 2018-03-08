<?php

namespace App\Models;

use App\Events\UserSaved;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $email_verified
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $google2fa_secret
 * @property int|null $facebook_id
 * @property array $facebook_data
 * @property string|null $google_id
 * @property array $google_data
 * @property array $notification_settings
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $assignedTickets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $departments
 * @property-read \App\Models\EmailVerification $emailVerification
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SlackWebhook[] $slackWebhooks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFacebookData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGoogle2faSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGoogleData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNotificationSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'notification_settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'facebook_data' => 'array',
        'google_data' => 'array',
        'notification_settings' => 'array',
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        $key = $notification->key ?? null;
        if ($key && !array_get($this->notification_settings, $key.'_email', false)) {
            return null;
        }

        return $this->email;
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        $key = $notification->key ?? null;
        $webhook = SlackWebhook::find(array_get($this->notification_settings, $key.'_slack', 0));

        if (!$webhook || !$this->can('use', $webhook)) {
            return null;
        }

        $notification->setSlackWebhook($webhook);

        return $webhook->uri;
    }

    /**
     * Tickets the user has assigned to them (as an agent).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'agent_id')->orderBy('id');
    }

    /**
     * Departments the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class)->orderBy('id');
    }

    /**
     * An email verification token model, if present.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emailVerification()
    {
        return $this->hasOne(EmailVerification::class);
    }

    /**
     * Roles this user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->orderBy('id');
    }

    /**
     * Tickets the user has assigned to them (as an agent).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slackWebhooks()
    {
        return $this->hasMany(SlackWebhook::class)->orderBy('name');
    }

    /**
     * Tickets the user has submitted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderBy('id');
    }

    /**
     * Sets the user's email address.
     *
     * @param string $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = $email;

        if ($this->isDirty('email')) {
            $this->email_verified = false;
        }
    }

    /**
     * Check whether a user has a given department.
     *
     * @param Department|string|int $department
     * @return bool
     */
    public function hasDepartment($department)
    {
        if (is_numeric($department)) {
            return $this->departments->contains('id', (int) $department);
        }

        if ($department instanceof Department) {
            return $this->departments->contains('id', $department->id);
        }

        return $this->departments->contains('id', Department::where('name', $department)->firstOrFail()->id);
    }

    /**
     * Check whether a user has a given role.
     *
     * @param Role|string|int $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_numeric($role)) {
            return $this->roles->contains('id', (int) $role);
        }

        if ($role instanceof Role) {
            return $this->roles->contains('id', $role->id);
        }

        return $this->roles->contains('id', Role::where('key', $role)->firstOrFail()->id);
    }

    /**
     * Check whether a user has a given permission.
     *
     * @param Permission|string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();

            if ($permission === null) {
                throw (new ModelNotFoundException())->setModel(Permission::class);
            }
        }

        return $permission->default || $permission->roles->filter(function ($role) {
                return $this->hasRole($role);
            })->isNotEmpty();
    }
}
