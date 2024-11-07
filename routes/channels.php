<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('my-channel.{userId}', function ($user) {
    return true;
});

Broadcast::channel('typing-event.{userId}', function ($user) {
    return true;
});
