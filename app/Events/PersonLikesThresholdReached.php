<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PersonLikesThresholdReached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $personId;
    public $likesCount;

    /**
     * Create a new event instance.
     */
    public function __construct(int $personId, int $likesCount)
    {
        $this->personId = $personId;
        $this->likesCount = $likesCount;
    }
}
