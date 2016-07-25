<?php
namespace Czim\CmsAclModule\Http\Requests\Api;

use Czim\CmsAclModule\Http\Requests\Request;

class UpdateUserRequest extends Request
{

    public function rules()
    {
        return [
            'password'   => 'string',
            'first_name' => 'string',
            'last_name'  => 'string',
            'roles'      => 'array',
            'roles.*'    => 'string',
        ];
    }

}
