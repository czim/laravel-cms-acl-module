<?php
namespace Czim\CmsAclModule\Contracts;

use Czim\CmsCore\Contracts\Modules\Data\AclPresenceInterface;

interface PermissionRepositoryInterface
{

    /**
     * Returns a list of all permissions known by the CMS.
     *
     * @return string[]
     */
    public function getAll();

    /**
     * Returns a list of all permissions currently assigned to roles and/or users.
     *
     * @return string[]
     */
    public function getAllInUse();

    /**
     * Returns a list of custom defined permissions.
     *
     * @return string[]
     */
    public function getCustom();

    /**
     * Returns a grouped list of permissions.
     *
     * @return AclPresenceInterface[]
     */
    public function getGrouped();

}
