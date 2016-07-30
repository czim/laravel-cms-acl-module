<?php
namespace Czim\CmsAclModule\Http\Controllers;

use Czim\CmsAclModule\Http\Requests\CreateRoleRequest;
use Czim\CmsAclModule\Http\Requests\UpdateRoleRequest;

class RolesController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->indexResponse($this->getIndexData());
    }

    /**
     * @param $key
     * @return mixed
     */
    public function show($key)
    {
        if ( ! $this->auth->roleExists($key)) {
            abort(404, 'Role does not exist');
        }

        return $this->showResponse($this->getShowData($key));
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view(
            config('cms-acl-module.views.roles.create'),
            [
                'permissions' => $this->auth->getAllPermissions(),
            ]
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

        return $this->createResponse($this->getShowData($key));
    }

    /**
     * @param int $key
     * @return mixed
     */
    public function edit($key)
    {
        if ( ! $this->auth->roleExists($key)) {
            abort(404, "Role not found");
        }

        return view(
            config('cms-acl-module.views.roles.edit'),
            [
                'role'        => $this->auth->getRole($key),
                'permissions' => $this->auth->getAllPermissions(),
            ]
        );
    }

    /**
     * @param UpdateRoleRequest $request
     * @param string            $key
     * @return mixed
     */
    public function update(UpdateRoleRequest $request, $key)
    {
        if ( ! $this->auth->roleExists($key)) {
            abort(404, 'Role does not exist');
        }

        // If the roles are defined in the request, sync for the user
        if ($request->has('permissions')) {
            $newPermissions     = $request->input('permissions', []);
            $currentPermissions = $this->auth->getAllPermissionsForRole($key);

            $revoke = array_diff($currentPermissions, $newPermissions);
            if (count($revoke)) {
                $this->auth->revokeFromRole($revoke, $key);
            }

            $grant = array_diff($newPermissions, $currentPermissions);
            if (count($grant)) {
                $this->auth->grantToRole($grant, $key);
            }
        }

        return $this->updateResponse($this->getShowData($key));
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function destroy($key)
    {
        if ( ! $this->auth->roleExists($key)) {
            abort(404, 'Role does not exist');
        }

        if ($this->auth->roleInUse($key)) {
            abort(412, 'Role is still in use');
        }

        if ( ! $this->auth->removeRole($key)) {
            abort(500, 'Failed to remove role');
        }

        return $this->deleteResponse();
    }

    // ------------------------------------------------------------------------------
    //      Response
    // ------------------------------------------------------------------------------

    /**
     * @param mixed $data
     * @return mixed
     */
    protected function indexResponse($data)
    {
        return view(
            config('cms-acl-module.views.roles.index'),
            [ 'roles' => $data ]
        );
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    protected function showResponse($data)
    {
        return view(
            config('cms-acl-module.views.roles.show'),
            [ 'role' => $data ]
        );
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    protected function createResponse($data)
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.roles.index')
        );
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    protected function updateResponse($data)
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.roles.index')
        );
    }

    /**
     * @return mixed
     */
    protected function deleteResponse()
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.roles.index')
        );
    }


    // ------------------------------------------------------------------------------
    //      Data Retrieval
    // ------------------------------------------------------------------------------

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
     * @return object
     */
    protected function decorateRole($role)
    {
        return (object) [
            'key'         => $role,
            'permissions' => $this->auth->getAllPermissionsForRole($role),
        ];
    }


    /**
     * @return array
     */
    protected function getIndexData()
    {
        return $this->decorateRoles(
            $this->auth->getAllRoles()
        );
    }

    /**
     * @param string $slug
     * @return array
     */
    protected function getShowData($slug)
    {
        return $this->decorateRole($slug);
    }
}
