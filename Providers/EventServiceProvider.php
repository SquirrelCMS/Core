<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for all modules.
     *
     * @return void
     */
    public function boot()
    {
        foreach (\Module::enabled() as $module) {
            $events = \Config::get($module->getLowerName() . '.events');
            if (is_array($events) && count($events) > 0)
                foreach ($events as $key => $handler) {
                    if (isset($this->listen[$key])) {
                        if (is_array($handler))
                            $this->listen[$key] = array_merge($this->listen[$key], $handler);
                        else
                            $this->listen[$key][] = $handler;
                    } else {
                        if (is_array($handler))
                            $this->listen[$key] = $handler;
                        else
                            $this->listen[$key] = [$handler];
                    }
                }
        }

        parent::boot();
    }
}
