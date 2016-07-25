<?php
namespace Czim\CmsAclModule\Http\Controllers\Api;

use Czim\CmsAclModule\Http\Controllers\Controller;
use Czim\CmsAclModule\Http\Requests\Api\CreateUserRequest;
use Czim\CmsAclModule\Http\Requests\Api\UpdateUserRequest;

class UsersController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->core->api()->response(
            $this->auth->getAllUsers()
        );
    }

    /**
     * Nothing to show really, a role is just a slugged string.
     * For now, returning the users that are assigned this role.
     *
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        if ( ! ($user = $this->auth->getUserById($id))) {
            abort(404, "User not found");
        }

        return $this->core->api()->response($user);
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

        return $this->core->api()->response($user, 201);
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

        return $this->core->api()->response($user, 200);
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

        return $this->core->api()->response('OK');
    }

}
