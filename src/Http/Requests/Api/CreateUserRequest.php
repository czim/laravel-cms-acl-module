<?php
namespace Czim\CmsAclModule\Http\Requests\Api;

use Czim\CmsAclModule\Http\Requests\Request;

class CreateUserRequest extends Request
{

    public function rules()

    {
        return [
            'email'      => 'required|string',
            'password'   => 'required|string',
            'first_name' => 'string',
            'last_name'  => 'string',
            'roles'      => 'array',
            'roles.*'    => 'string',
        ];
    }

}
