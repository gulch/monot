<?php

declare(strict_types=1);

namespace Monot\Notification;

use Monot\Contract\Notification;

class NullNotification implements Notification
{
    public function notify(string $text): bool
    {
        return true;
    }
}