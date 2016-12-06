<?php
namespace Czim\CmsAclModule\Repositories;

use Czim\CmsAclModule\Contracts\PermissionRepositoryInterface;
use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;

class PermissionRepository implements PermissionRepositoryInterface
{

    /**
     * @var AuthenticatorInterface
     */
    protected $auth;

    /**
     * @param AuthenticatorInterface $auth
     */
    public function __construct(AuthenticatorInterface $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Returns a list of all permissions known by the CMS.
     *
     * @return string[]
     */
    public function getAll()
    {
        $permissions = $this->auth->getAllPermissions();

        $permissions = array_merge($permissions, $this->getCustom());

        return array_unique($permissions);
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
