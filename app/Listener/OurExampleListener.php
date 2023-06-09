<?php

namespace App\Listener;

use App\Events\OurExampleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class OurExampleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OurExampleEvent  $event
     * @return void
     */
    public function handle(OurExampleEvent $event)
    {
        Log::debug("Salut , {$event->username} vient de se {$event->action}");
    }
}
