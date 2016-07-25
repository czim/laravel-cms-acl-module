<?php
namespace Czim\CmsAclModule;

use Czim\CmsAclModule\Support\RouteBuilders\ApiRouteBuilder;
use Czim\CmsAclModule\Support\RouteBuilders\WebRouteBuilder;
use Czim\CmsCore\Contracts\Modules\Data\AclPresenceInterface;
use Czim\CmsCore\Contracts\Modules\Data\MenuPresenceInterface;
use Czim\CmsCore\Contracts\Modules\ModuleInterface;
use Illuminate\Routing\Router;

class AclModule implements ModuleInterface
{

    /**
     * Returns unique identifying key for the module.
     * This should also be able to perform as a slug for it.
     *
     * @return string
     */
    public function getKey()
    {
        return 'acl-simple';
    }

    /**
     * Returns display name for the module.
     *
     * @return string
     */
    public function getName()
    {
        return 'Access Control Management';
    }

    /**
     * Returns the FQN for a class mainly associated with this module.
     *
     * @return string|null
     */
    public function getAssociatedClass()
    {
        return null;
    }

    /**
     * Returns a list of FQNs for service providers that should always be registered.
     *
     * @return string[]
     */
    public function getServiceProviders()
    {
        return [];
    }

    /**
     * Generates web routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function buildWebRoutes(Router $router)
    {
        $builder = new WebRouteBuilder();

        $router->group(
            [
                'as'        => 'acl.',
                'prefix'    => 'acl',
                'namespace' => '\\Czim\\CmsAclModule\\Http\\Controllers',
            ],
            function (Router $router) use ($builder) {

                $builder->buildUserRoutes($router);
                $builder->buildRoleRoutes($router);
            }
        );
    }

    /**
     * Generates API routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function buildApiRoutes(Router $router)
    {
        $builder = new ApiRouteBuilder();

        $router->group(
            [
                'as'        => 'acl.',
                'prefix'    => 'acl',
                'namespace' => '\\Czim\\CmsAclModule\\Http\\Controllers\\Api',
            ],
            function (Router $router) use ($builder) {

                $builder->buildUserRoutes($router);
                $builder->buildRoleRoutes($router);
            }
        );
    }

    /**
     * @return null|array|AclPresenceInterface|AclPresenceInterface[]
     */
    public function getAclPresence()
    {
        return null;
    }

    /**
     * Returns data for CMS menu presence.
     *
     * @return null|array|MenuPresenceInterface[]|MenuPresenceInterface[]
     */
    public function getMenuPresence()
    {
        // TODO: Implement getMenuPresence() method.
        return null;
    }
}
