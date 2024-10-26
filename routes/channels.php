<?php

use App\Broadcasting\MyChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('my-channel.{userId}', function ($user) {
    return true;
});


Broadcast::channel('typing-event.{userId}', function ($user) {
    return true;
});
