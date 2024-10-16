<?php

declare(strict_types=1);

namespace JohnWink\JwNotifications\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JohnWink\JwNotifications\Models\SubscribedChannels;

trait CanSubscribeToChannels
{
    public function subscribeToAllChannels(): void
    {
        //
    }

    public function notificationSubscribed(string $notification): bool
    {
        return $this->subscribedNotificationChannels()->where('notification', $notification)->exists();
    }

    public function subscribedNotificationChannels(): HasMany
    {
        return $this->notificationChannels()
            ->where(fn (Builder $query) => $query->whereNull('paused_until')->orWhereTime('paused_until', '<', now()))
            ->where('is_subscribed', true);
    }

    public function notificationChannels(): HasMany
    {
        return $this->hasMany(SubscribedChannels::class);
    }

    public function channelSubscribed(string $notification, string $channel): bool
    {
        return $this->subscribedNotificationChannels()->where('notification', $notification)->where('channel', $channel)->exists();
    }

    public function subscribeToChannel(string $notification, string $channel)
    {
        $this->subscribedNotificationChannels()->create([
            'channel'       => $channel,
            'notification'  => $notification,
            'is_subscribed' => true,
        ]);
    }
}
