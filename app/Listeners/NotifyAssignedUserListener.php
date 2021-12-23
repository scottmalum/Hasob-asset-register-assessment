<?php

namespace App\Listeners;

use App\Events\AssetWasAssignedToUserEvent;
use App\Mail\AssignAssetMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAssignedUserListener
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
        Mail::to($event->data['user']->email)->send(new AssignAssetMail($event->data));
    }
}
