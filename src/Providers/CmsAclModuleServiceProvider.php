<?php
namespace Czim\CmsAclModule\Providers;

use Czim\CmsAclModule\Contracts\PermissionRepositoryInterface;
use Czim\CmsAclModule\Repositories\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class CmsAclModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->bootConfig();
    }

    public function register()
    {
        $this->registerConfig()
             ->registerInterfaceBindings()
             ->loadViews();
    }


    /**
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            realpath(dirname(__DIR__) . '/../config/cms-acl-module.php'),
            'cms-acl-module'
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerInterfaceBindings()
    {
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);

        return $this;
    }

    /**
     * Loads basic CMS ACL views.
     *
     * @return $this
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(
            realpath(dirname(__DIR__) . '/../resources/views'),
            'cms-acl'
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function bootConfig()
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../config/cms-acl-module.php') => config_path('cms-acl-module.php'),
        ]);

        return $this;
    }

}
