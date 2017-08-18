<?php

namespace Monot\Contract;

interface Notification
{
    public function notify(string $text): bool;
}