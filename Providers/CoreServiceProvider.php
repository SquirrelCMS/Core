<?php
/**
 * Created by Squirrel Group.
 * User: mr.ubkey@gmail.com
 * Date: 03/10/2016
 * Time: 3:22 CH
 */

namespace Modules\Core\Providers;


use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerMiddleware($router);
        $this->registerComposer();
        $this->registerHelper();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerAliases();
        $this->registerService();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        foreach (\Module::enabled() as $module) {
            $this->publishes([
                $module->getExtraPath('Config') . '/config.php' => config_path($module->getLowerName() . '.php'),
            ]);
            $this->mergeConfigFrom(
                $module->getExtraPath('Config') . '/config.php', $module->getLowerName()
            );
        }
    }

    /**
     * Register middleware.
     *
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach (\Module::enabled() as $module) {
            $middlewareGroups = \Config::get($module->getLowerName() . '.middlewareGroups');
            if (is_array($middlewareGroups) && count($middlewareGroups) > 0)
                foreach ($middlewareGroups as $key => $middlewares)
                    foreach ($middlewares as $m)
                        $router->pushMiddlewareToGroup($key, $m);

            $routeMiddleware = \Config::get($module->getLowerName() . '.routeMiddleware');
            if (is_array($routeMiddleware) && count($routeMiddleware) > 0)
                foreach ($routeMiddleware as $key => $middleware)
                    $router->middleware($key, $middleware);
        }
    }

    /**
     * Register composer for module
     */
    public function registerComposer()
    {
        foreach (\Module::enabled() as $module) {
            $composers = \Config::get($module->getLowerName() . '.composers');
            if (is_array($composers))
                foreach ($composers as $composer => $view)
                    View::composer($view, $composer);
        }
    }

    /**
     * Register aliases for module
     */
    public function registerAliases()
    {
        foreach (\Module::enabled() as $module) {
            $aliases = \Config::get($module->getLowerName() . '.aliases');
            if (is_array($aliases))
                foreach ($aliases as $alias => $class)
                    AliasLoader::getInstance()->alias($alias, $class);
        }
    }

    /**
     * Register helper for all module
     */
    public function registerHelper()
    {
        foreach (\Module::enabled() as $module) {
            //load helper with config mapper
            $helpers = \Config::get($module->getLowerName() . '.helpers');
            if (is_array($helpers)) {
                foreach ($helpers as $h) {
                    $file = $module->getExtraPath('Helpers') . "/{$h}.php";
                    if (file_exists($file))
                        require_once $file;
                }
            } //load helper automatically
            else
                foreach (glob($module->getExtraPath('Helpers') . '/*.php') as $file)
                    require_once($file);
        }
    }

    /**
     * Register module service
     */
    public function registerService()
    {
        foreach (\Module::enabled() as $module) {
            //load helper with config mapper
            $services = \Config::get($module->getLowerName() . '.services');
            if (is_array($services)) {
                foreach ($services as $k => $c)
                    $this->app->singleton($k, $c);
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
