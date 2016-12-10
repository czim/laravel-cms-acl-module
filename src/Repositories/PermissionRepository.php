<?php
namespace Czim\CmsAclModule\Repositories;

use Czim\CmsAclModule\Contracts\PermissionRepositoryInterface;
use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;
use Czim\CmsCore\Contracts\Modules\ModuleManagerInterface;

class PermissionRepository implements PermissionRepositoryInterface
{

    /**
     * @var AuthenticatorInterface
     */
    protected $auth;

    /**
     * @var ModuleManagerInterface
     */
    protected $modules;

    /**
     * @param AuthenticatorInterface $auth
     * @param ModuleManagerInterface $modules
     */
    public function __construct(AuthenticatorInterface $auth, ModuleManagerInterface $modules)
    {
        $this->auth    = $auth;
        $this->modules = $modules;
    }


    /**
     * Returns a list of all permissions known by the CMS.
     *
     * @return string[]
     */
    public function getAll()
    {
        $permissions = $this->modules->getAllPermissions();

        $permissions = array_merge($permissions, $this->getCustom());

        return array_unique($permissions);
    }

    /**
     * Returns a list of all permissions currently assigned to roles and/or users.
     *
     * @return string[]
     */
    public function getAllInUse()
    {
        return $this->auth->getAllPermissions();
    }

    /**
     * Returns a list of custom defined permissions.
     *
     * @return string[]
     */
    public function getCustom()
    {
        return config('cms-acl-module.permissions', []);
    }

}
