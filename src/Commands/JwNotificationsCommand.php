<?php

namespace JohnWink\JwNotifications\Commands;

use Illuminate\Console\Command;

class JwNotificationsCommand extends Command
{
    public $signature = 'jw-notifications';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
