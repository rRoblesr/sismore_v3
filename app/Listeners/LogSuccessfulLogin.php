<?php

namespace App\Listeners;

use App\Models\Administracion\LoginRecords;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
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
        // $user->increment('login_count');
        LoginRecords::create([
            'usuario' => $user->id,
            'login' => now(),
            'ip' => request()->ip(),
            'user_agent' => substr(request()->userAgent(), 0, 255),
        ]);
    }
}
