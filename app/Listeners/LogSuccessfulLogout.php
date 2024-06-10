<?php

namespace App\Listeners;

use App\Models\Administracion\LoginRecords;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $latestLoginRecord = LoginRecords::where('usuario', $user->id)->latest()->first();
        if ($latestLoginRecord) {
            $latestLoginRecord->update(['logout' => now()]);
        }
    }
}
