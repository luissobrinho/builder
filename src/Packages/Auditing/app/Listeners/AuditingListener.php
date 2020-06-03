<?php


namespace App\Listeners;

use OwenIt\Auditing\Events\Auditing;

class AuditingListener
{
    /**
     * Create the Auditing event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the Auditing event.
     *
     * @param Auditing $event
     * @return void
     */
    public function handle(Auditing $event)
    {
        // Implement logic
    }
}