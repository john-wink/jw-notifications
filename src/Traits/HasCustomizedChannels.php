<?php

declare(strict_types=1);

namespace JohnWink\JwNotifications\Traits;

trait HasCustomizedChannels
{
    public function via(object $notifiable): array
    {
        return $notifiable->subscribedNotificationChannels()->where('notification', get_class($this))->pluck('channel')->unique()->toArray();
    }
}
