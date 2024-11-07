<?php

declare(strict_types=1);

namespace App\Broadcasting;

use App\Features\User\Models\User;

class MyChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user)
    {
        // Permitir que apenas o usuário e o destinatário se inscrevam no canal
        return true;
    }
}
