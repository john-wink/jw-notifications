<?php

namespace JohnWink\JwNotifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JohnWink\JwNotifications\JwNotifications
 */
class JwNotifications extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JohnWink\JwNotifications\JwNotifications::class;
    }
}
