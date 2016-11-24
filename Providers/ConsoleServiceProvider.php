<?php

namespace Modules\Core\Providers;


use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Register the commands all modules.
     */
    public function register()
    {
        foreach (\Module::enabled() as $module) {
            $commands = \Config::get($module->getLowerName() . '.commands');
            if (is_array($commands))
                foreach ($commands as $c)
                    $this->commands[] = $c;
        }

        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
