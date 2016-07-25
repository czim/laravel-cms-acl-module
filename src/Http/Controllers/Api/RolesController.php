<?php
namespace Czim\CmsAclModule\Http\Controllers\Api;

use Czim\CmsAclModule\Http\Controllers\Controller;
use Czim\CmsAclModule\Http\Requests\Api\CreateRoleRequest;
use Czim\CmsAclModule\Http\Requests\Api\UpdateRoleRequest;

class RolesController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->core->api()->response(
            $this->decorateRoles(
                $this->auth->getAllRoles()
            )
        );
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function show($slug)
    {
        if ( ! $this->auth->roleExists($slug)) {
            abort(404, 'Role does not exist');
        }

        return $this->core->api()->response(
            $this->decorateRole($slug)
        );
    }

    /**
     * @param CreateRoleRequest $request
     * @return mixed
     */
    public function store(CreateRoleRequest $request)
    {
        $key  = $request->input('key');
        $name = $request->input('name');

        if ($this->auth->roleExists($key)) {
            abort(412, 'Role with that key already exists');
        }

        if ( ! $this->auth->createRole($key, $name)) {
            abort(500, 'Failed to create new role');
        }

        if ( ! $this->auth->grantToRole($request->input('permissions', []), $key)) {
            abort(500, 'Role created, but failed to grant permissions');
        }

        return $this->core->api()->response($key, 201);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param string            $slug
     * @return mixed
     */
    public function update(UpdateRoleRequest $request, $slug)
    {
        if ( ! $this->auth->roleExists($slug)) {
            abort(404, 'Role does not exist');
        }

        // If the roles are defined in the request, sync for the user
        if ($request->has('permissions')) {
            $newPermissions     = $request->input('permissions', []);
            $currentPermissions = $this->auth->getAllPermissionsForRole($slug);

            $revoke = array_diff($currentPermissions, $newPermissions);
            if (count($revoke)) {
                $this->auth->revokeFromRole($revoke, $slug);
            }

            $grant = array_diff($newPermissions, $currentPermissions);
            if (count($grant)) {
                $this->auth->grantToRole($grant, $slug);
            }
        }

        return $this->core->api()->response($slug, 200);
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function destroy($slug)
    {
        if ( ! $this->auth->roleExists($slug)) {
            abort(404, 'Role does not exist');
        }

        if ($this->auth->roleInUse($slug)) {
            abort(412, 'Role is still in use');
        }

        if ( ! $this->auth->removeRole($slug)) {
            abort(500, 'Failed to remove role');
        }

        return $this->core->api()->response('OK');
    }


    /**
     * @param array $roles
     * @return array
     */
    protected function decorateRoles(array $roles)
    {
        return array_map([$this, 'decorateRole'], $roles);
    }

    /**
     * @param string $role
     * @return array
     */
    protected function decorateRole($role)
    {
        return [
            'key'         => $role,
            'permissions' => $this->auth->getAllPermissionsForRole($role),
        ];
    }
}
