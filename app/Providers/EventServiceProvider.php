<?php

namespace App\Providers;

use App\Events\AssetWasAssignedToUserEvent;
use App\Listeners\NotifyAssignedUserListener;
use App\Events\EmailVerificationEvent;
use App\Listeners\EmailVerificationListener;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AssetWasAssignedToUserEvent::class => [
            NotifyAssignedUserListener::class,
        ],
        EmailVerificationEvent::class => [
            EmailVerificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
