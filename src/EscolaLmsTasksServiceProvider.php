<?php

namespace EscolaLms\Tasks;

use EscolaLms\Tasks\Providers\AuthServiceProvider;
use EscolaLms\Tasks\Repositories\Contracts\TaskRepositoryContract;
use EscolaLms\Tasks\Repositories\TaskRepository;
use EscolaLms\Tasks\Services\Contracts\TaskServiceContract;
use EscolaLms\Tasks\Services\TaskService;
use Illuminate\Support\ServiceProvider;

class EscolaLmsTasksServiceProvider extends ServiceProvider
{
    const CONFIG_KEY = 'escolalms_tasks';

    public const REPOSITORIES = [
        TaskRepositoryContract::class => TaskRepository::class,
    ];

    public const SERVICES = [
        TaskServiceContract::class => TaskService::class
    ];

    public $singletons = self::SERVICES + self::REPOSITORIES;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', self::CONFIG_KEY);

        $this->app->register(AuthServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function bootForConsole()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/config.php' => config_path(self::CONFIG_KEY . '.php'),
        ], self::CONFIG_KEY . '.config');
    }
}
