<?php
namespace Czim\CmsAclModule\Repositories;

use Czim\CmsAclModule\Contracts\PermissionRepositoryInterface;
use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;
use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Contracts\Modules\Data\AclPresenceInterface;
use Czim\CmsCore\Contracts\Modules\ModuleManagerInterface;
use Czim\CmsCore\Support\Data\AclPresence;
use Czim\CmsCore\Support\Enums\AclPresenceType;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{

    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @var AuthenticatorInterface
     */
    protected $auth;

    /**
     * @var ModuleManagerInterface
     */
    protected $modules;

    /**
     * Whether the permission data has been prepared, used to prevent
     * performing preparations more than once.
     *
     * @var bool
     */
    protected $prepared = false;

    /**
     * List of groups as a nested structure of presences.
     *
     * Note that only 1-level depth for presence groups is supported.
     * Top level presences must be groups, and may contain only permission presences.
     *
     * @var Collection|AclPresenceInterface[]
     */
    protected $permissionGroups;

    /**
     * Ungrouped permissions (those not assigned to any groups).
     * These are at the end added to a custom 'misc' group.
     *
     * @var string[]
     */
    protected $ungroupedPermissions;

    /**
     * An index of the permissions with the group index they are
     * currently assigned to.
     *
     * @var array   keyed by permission key string
     */
    protected $groupedPermissionIndex;


    /**
     * @param CoreInterface          $core
     * @param AuthenticatorInterface $auth
     * @param ModuleManagerInterface $modules
     */
    public function __construct(CoreInterface $core, AuthenticatorInterface $auth, ModuleManagerInterface $modules)
    {
        $this->core    = $core;
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

    /**
     * Returns a grouped list of permissions.
     *
     * @return AclPresenceInterface[]
     */
    public function getGrouped()
    {
        if ( ! $this->prepared) {
            $this->prepareForPresentation();
        }

        return $this->permissionGroups;
    }


    // ------------------------------------------------------------------------------
    //      Processing and preparation
    // ------------------------------------------------------------------------------

    /**
     * Prepares CMS data for presentation in the menu views.
     */
    protected function prepareForPresentation()
    {
        if ($this->prepared) {
            return;
        }

        $this->permissionGroups       = new Collection;
        $this->ungroupedPermissions   = [];
        $this->groupedPermissionIndex = [];

        $this->loadPermissionsFromModules()
             ->loadCustomPermissions()
             ->loadCustomPermissionGroups()
             ->addUngroupedPermissionGroup()
             ->filterEmptyGroups();
    }

    /**
     * @return $this
     */
    protected function loadPermissionsFromModules()
    {
        foreach ($this->core->modules()->getModules() as $moduleKey => $module) {

            $presencesForModule = $module->getAclPresence();

            // If a module has no presence, skip it
            if ( ! $presencesForModule) {
                continue;
            }

            foreach ($this->normalizeAclPresence($presencesForModule) as $presence) {

                // strings are just ungrouped presences
                if (is_string($presence)) {
                    $this->ungroupedPermissions[] = $presence;
                    continue;
                }

                $permissions = $presence->permissions();

                $this->permissionGroups->push($presence);

                $index = $this->permissionGroups->count() - 1;

                if ($permissions) {

                    if ( ! is_array($permissions)) {
                        $permissions = [ $permissions ];
                    }

                    foreach ($permissions as $permission) {
                        $this->groupedPermissionIndex[ $permission ] = $index;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadCustomPermissions()
    {
        $permissions = $this->getCustom();

        foreach ($permissions as $permission) {

            // If the permission is already part of a group, do nothing
            if (array_key_exists($permission, $this->groupedPermissionIndex)) {
                continue;
            }

            $this->ungroupedPermissions[] = $permission;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadCustomPermissionGroups()
    {
        $presences = config('cms-acl-module.groups', []);

        $presences = $this->normalizeAclPresence($presences);

        foreach ($presences as $presence) {

            if (is_string($presence)) {
                continue;
            }

            if ($presence->type() == AclPresenceType::PERMISSION) {

                $permissions = $presence->permissions();

                if ($permissions) {
                    if ( ! is_array($permissions)) {
                        $permissions = [ $permissions ];
                    }

                    $this->ungroupedPermissions = array_merge($this->ungroupedPermissions, $permissions);
                }
                continue;
            }

            $this->permissionGroups->push($presence);

            $permissions = $presence->permissions();

            $index = $this->permissionGroups->count() - 1;

            if ($permissions) {

                if ( ! is_array($permissions)) {
                    $permissions = [ $permissions ];
                }

                foreach ($permissions as $permission) {

                    // Remove module grouped that have been overridden
                    if ($currentIndex = $this->groupedPermissionIndex[ $permission ]) {
                        /** @var AclPresenceInterface $group */
                        $group = $this->permissionGroups->get($currentIndex);
                        $group->removePermission($permission);
                    }

                    // Remove ungrouped that have now been grouped
                    if (in_array($permission, $this->ungroupedPermissions)) {
                        $this->ungroupedPermissions = array_diff($this->ungroupedPermissions, [ $permission ]);
                    }

                    // Set new index for group
                    $this->groupedPermissionIndex[ $permission ] = $index;
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUngroupedPermissionGroup()
    {
        $presence = $this->createUngroupedGroupPresence();

        $presence->setPermissions($this->ungroupedPermissions);

        $this->permissionGroups->push($presence);

        return $this;
    }

    /**
     * Removes any empty (permissionless) groups from the compiled ACL structure.
     *
     * @return $this
     */
    protected function filterEmptyGroups()
    {
        $remove = [];
        foreach ($this->permissionGroups as $key => $presence) {
            if ( ! $this->filterNestedEmptyGroups($presence)) {
                $remove[] = $key;
            }
        }
        $this->permissionGroups->forget($remove);

        return $this;
    }

    /**
     * Removes any empty group children from a tree structure, returning
     * the number of non-group entries.
     *
     * @param AclPresenceInterface $presence
     * @return int  the number of non-group children found on the levels below
     */
    protected function filterNestedEmptyGroups(AclPresenceInterface $presence)
    {
        if ($presence['type'] !== AclPresenceType::GROUP) {
            return 1;
        }

        $permissions = $presence->permissions();

        if ( ! $permissions) {
            return 0;
        }

        if (is_string($permissions)) {
            return 1;
        }

        return count($permissions);
    }


    /**
     * Creates an AclPresence instance that represents a group.
     *
     * @param string                               $id
     * @param string                               $label
     * @param array|array[]|AclPresenceInterface[] $children
     * @return AclPresenceInterface
     */
    protected function createGroupPresence($id, $label, array $children = [])
    {
        return new AclPresence([
            'type'     => AclPresenceType::GROUP,
            'id'       => $id,
            'label'    => $label,
            'children' => $children,
        ]);
    }

    /**
     * Creates an AclPresence group instance for ungrouped permissions.
     *
     * @param string $id
     * @return AclPresenceInterface
     */
    protected function createUngroupedGroupPresence($id = null)
    {
        $id = $id ?: 'automatic-ungrouped-permissions';

        return new AclPresence([
            'type'       => AclPresenceType::GROUP,
            'id'         => $id,
            'label'      => 'acl.ungrouped-permissions',
            'translated' => true,
        ]);
    }

    /**
     * Normalizes menu presence data to an array of AclPresence instances.
     * Lists of permission slugs will remain unchanged.
     *
     * @param mixed $data
     * @return AclPresenceInterface[]
     */
    protected function normalizeAclPresence($data)
    {
        if ($data instanceof AclPresenceInterface) {

            $data = [ $data ];

        } elseif (is_array($data) && ! Arr::isAssoc($data)) {

            $presences = [];

            // If presences are just groupless permissions return them as-is
            foreach ($data as $nestedData) {

                if (is_string($nestedData)) {
                    $presences[] = $nestedData;
                } else {
                    $presences[] = new AclPresence($nestedData);
                }
            }

            $data = $presences;

        } else {

            $data = [ new AclPresence($data) ];
        }

        /** @var AclPresenceInterface[]|string[] $data */
        return $data;
    }

}
