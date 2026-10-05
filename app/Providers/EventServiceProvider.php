<?php

namespace App\Providers;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $audit = function (string $action, string $event, array $payload): void {
            $model = $payload[0] ?? null;

            if ($model instanceof \Illuminate\Database\Eloquent\Model) {
                app(AuditLogger::class)->log($action, $model);
            }
        };

        Event::listen('eloquent.created: *', function ($event, $payload) use ($audit) {
            $audit('created', $event, $payload);
        });

        Event::listen('eloquent.updated: *', function ($event, $payload) use ($audit) {
            $audit('updated', $event, $payload);
        });

        Event::listen('eloquent.deleted: *', function ($event, $payload) use ($audit) {
            $audit('deleted', $event, $payload);
        });
    }
}
