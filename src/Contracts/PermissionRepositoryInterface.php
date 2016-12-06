<?php
namespace Czim\CmsAclModule\Contracts;

interface PermissionRepositoryInterface
{

    /**
     * Returns a list of all permissions known by the CMS.
     *
     * @return string[]
     */
    public function getAll();

    /**
     * Returns a list of custom defined permissions.
     *
     * @return string[]
     */
    public function getCustom();

}
