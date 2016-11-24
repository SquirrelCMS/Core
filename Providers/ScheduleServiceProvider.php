<?php
/**
 * Created by PhpStorm.
 * User: mrubk
 * Date: 21/10/2016
 * Time: 5:16 CH
 */

namespace Modules\Core\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Core\Schedule\Contracts\ScheduleContract;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(Schedule::class);

        //register schedule all module
        $this->registerSchedule();
    }

    public function registerSchedule()
    {
        foreach (\Module::enabled() as $module) {
            $tasks = \Config::get($module->getLowerName() . '.schedule');
            if (is_array($tasks))
                foreach ($tasks as $t) {
                    $task = app()->make($t);
                    if ($task instanceof ScheduleContract) {
                        $task->register();
                    }
                }
        }
    }
}