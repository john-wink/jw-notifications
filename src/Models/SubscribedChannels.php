<?php

namespace JohnWink\JwNotifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscribedChannels extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'redirect_uuid',
        'notification',
        'channel',
        'is_subscribed',
        'paused_until',
    ];

    public function getTable()
    {
        return config('jw-notifications.table');
    }
}
