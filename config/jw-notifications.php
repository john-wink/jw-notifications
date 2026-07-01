<?php

use App\Models\User;

// config for JohnWink/JwNotifications
return [
    'table'      => 'subscribed_channels',
    'notifiable' => User::class,
];
