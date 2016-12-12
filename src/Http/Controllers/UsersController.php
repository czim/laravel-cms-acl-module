<?php
namespace Czim\CmsAclModule\Http\Controllers;

use Czim\CmsAclModule\Http\Requests\CreateUserRequest;
use Czim\CmsAclModule\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->indexResponse(
            $this->auth->getAllUsers()
        );
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        if ( ! ($user = $this->auth->getUserById($id))) {
            abort(404, "User not found");
        }

        return $this->showResponse($user);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view(
            config('cms-acl-module.views.users.create'),
            [
                'create' => true,
                'roles'  => $this->auth->getAllRoles(),
            ]
        );
    }

    /**
     * @param CreateUserRequest $request
     * @return mixed
     */
    public function store(CreateUserRequest $request)
    {
        $email    = $request->input('email');
        $password = $request->input('password');

        if ($this->auth->getUserByUserName('email')) {
            abort(412, "User with that e-mail address already exists");
        }

        $data = [
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
        ];

        if ( ! ($user = $this->auth->createUser($email, $password, $data))) {
            abort(500, 'Failed to create new user');
        }

        if ( ! $this->auth->assign($request->input('roles', []), $user)) {
            abort(500, 'User created, but failed to assign roles');
        }

        return $this->createResponse($user);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function edit($id)
    {
        if ( ! ($user = $this->auth->getUserById($id))) {
            abort(404, "User not found");
        }

        return view(
            config('cms-acl-module.views.users.edit'),
            [
                'create' => false,
                'user'   => $user,
                'roles'  => $this->auth->getAllRoles(),
            ]
        );
    }

    /**
     * @param UpdateUserRequest $request
     * @param int               $id
     * @return mixed
     */
    public function update(UpdateUserRequest $request, $id)
    {
        if ( ! ($user = $this->auth->getUserById($id))) {
            abort(404, "User not found");
        }

        if ($user->isAdmin()) {
            abort(403, "Admin may not be updated");
        }

        // If the roles are defined in the request, sync for the user
        if ($request->has('roles')) {
            $newRoles     = $request->input('roles', []);
            $currentRoles = $user->getAllRoles();

            $unassign = array_diff($currentRoles, $newRoles);
            if (count($unassign)) {
                $this->auth->unassign($unassign, $user);
            }

            $assign = array_diff($newRoles, $currentRoles);
            if (count($assign)) {
                $this->auth->assign($assign, $user);
            }
        }

        // Update password, if given
        if ($request->has('password')) {
            $this->auth->updatePassword($user->getUsername(), $request->input('password'));
        }

        // Update other properties, if given
        if ($request->has('first_name') || $request->has('last_name')) {
            $this->auth->updateUser($user->getUsername(), $request->only('first_name', 'last_name'));
        }

        // Get fresh data
        $user = $this->auth->getUserById($id);

        return $this->updateResponse($user);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        if ( ! ($user = $this->auth->getUserById($id))) {
            abort(404, "User not found");
        }

        if ($user->isAdmin()) {
            abort(403, "Admin may not be deleted");
        }

        if ( ! $this->auth->deleteUser($user->getUsername())) {
            abort(500, 'Failed to delete user');
        }

        return $this->deleteResponse();
    }


    // ------------------------------------------------------------------------------
    //      Response
    // ------------------------------------------------------------------------------

    /**
     * @param $data
     * @return mixed
     */
    protected function indexResponse($data)
    {
        return view(
            config('cms-acl-module.views.users.index'),
            [ 'users' => $data ]
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function showResponse($data)
    {
        return view(
            config('cms-acl-module.views.users.show'),
            [ 'user' => $data ]
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function createResponse($data)
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.users.index'),
            [ $data['id'] ]
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function updateResponse($data)
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.users.index'),
            [ $data['id'] ]
        );
    }

    /**
     * @return mixed
     */
    protected function deleteResponse()
    {
        return redirect()->route(
            $this->core->prefixRoute('acl.users.index')
        );
    }


}
