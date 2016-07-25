<?php
namespace Czim\CmsAclModule\Providers;

use Illuminate\Support\ServiceProvider;

class CmsAclModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->bootConfig();
    }

    public function register()
    {
        $this->registerConfig();
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
    protected function bootConfig()
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../config/cms-acl-module.php') => config_path('cms-acl-module.php'),
        ]);

        return $this;
    }

}
