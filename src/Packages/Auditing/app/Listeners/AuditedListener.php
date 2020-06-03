<?php


namespace App\Listeners;

use OwenIt\Auditing\Events\Audited;

class AuditedListener
{
    /**
     * Create the Audited event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the Audited event.
     *
     * @param Audited $event
     * @return void
     */
    public function handle(Audited $event)
    {
        // Implement logic
    }
}