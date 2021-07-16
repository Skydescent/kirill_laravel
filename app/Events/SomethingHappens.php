<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SomethingHappens implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $whatHappens;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( string $whatHappens)
    {
        $this->whatHappens = $whatHappens;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('hello');
    }

//    public function broadcastWith()
//    {
//        return ['what' => $this->whatHappens, 'other' => 'other'];
//    }

//    public function broadcastWhen()
//    {
//        return $this->value > 100;
//    }
}
