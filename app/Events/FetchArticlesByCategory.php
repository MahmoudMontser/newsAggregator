<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FetchArticlesByCategory
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $category;

    /**
     * Create a new event instance.
     */
    public function __construct($category)
    {
        $this->category = $category;
    }


}
