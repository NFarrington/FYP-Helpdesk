<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SlackWebhook
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $uri
 * @property string $recipient
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SlackWebhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SlackWebhook whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SlackWebhook whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SlackWebhook whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SlackWebhook whereUserId($value)
 * @mixin \Eloquent
 */
class SlackWebhook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'uri', 'recipient'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
