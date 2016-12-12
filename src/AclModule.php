<?php
namespace Czim\CmsAclModule;

use Czim\CmsAclModule\Support\Route\ApiRouteMapper;
use Czim\CmsAclModule\Support\Route\WebRouteMapper;
use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Contracts\Modules\Data\AclPresenceInterface;
use Czim\CmsCore\Contracts\Modules\Data\MenuPresenceInterface;
use Czim\CmsCore\Contracts\Modules\ModuleInterface;
use Czim\CmsCore\Support\Enums\AclPresenceType;
use Czim\CmsCore\Support\Enums\MenuPresenceType;
use Illuminate\Routing\Router;

class AclModule implements ModuleInterface
{

    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @param CoreInterface $core
     */
    public function __construct(CoreInterface $core)
    {
        $this->core = $core;
    }

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
     * Returns release/version number of module.
     *
     * @return string
     */
    public function getVersion()
    {
        return '0.0.1';
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
     * Generates web routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function mapWebRoutes(Router $router)
    {
        (new WebRouteMapper())->mapRoutes($router);
    }

    /**
     * Generates API routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function mapApiRoutes(Router $router)
    {
        (new ApiRouteMapper())->mapRoutes($router);
    }

    /**
     * @return null|array|AclPresenceInterface|AclPresenceInterface[]
     */
    public function getAclPresence()
    {
        return [
            [
                'id'          => 'simple-acl-roles',
                'label'       => 'Managing roles',
                'type'        => AclPresenceType::GROUP,
                'permissions' => [
                    'acl.roles.show',
                    'acl.roles.create',
                    'acl.roles.edit',
                    'acl.roles.delete',
                ],
            ],
            [
                'id'          => 'simple-acl-users',
                'label'       => 'Managing users',
                'type'        => AclPresenceType::GROUP,
                'permissions' => [
                    'acl.users.show',
                    'acl.users.create',
                    'acl.users.edit',
                    'acl.users.delete',
                ],
            ],
        ];
    }

    /**
     * Returns data for CMS menu presence.
     *
     * @return null|array|MenuPresenceInterface[]|MenuPresenceInterface[]
     */
    public function getMenuPresence()
    {
        return [
            'id'       => 'simple-acl',
            'type'     => MenuPresenceType::GROUP,
            'label'    => 'Access Control',
            'children' => [
                [
                    'id'          => 'simple-acl-users',
                    'type'        => MenuPresenceType::ACTION,
                    'label'       => 'Users',
                    'permissions' => 'acl.users.show',
                    'action'      => $this->core->prefixRoute('acl.users.index'),
                    'parameters'  => [],
                ],
                [
                    'id'          => 'simple-acl-create-user',
                    'type'        => MenuPresenceType::ACTION,
                    'label'       => 'New User',
                    'permissions' => 'acl.users.create',
                    'action'      => $this->core->prefixRoute('acl.users.create'),
                    'parameters'  => [],
                ],
                [
                    'id'          => 'simple-acl-roles',
                    'type'        => MenuPresenceType::ACTION,
                    'label'       => 'Roles',
                    'permissions' => 'acl.roles.show',
                    'action'      => $this->core->prefixRoute('acl.roles.index'),
                    'parameters'  => [],
                ],
            ]
        ];
    }
}
