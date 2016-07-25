<?php
namespace Czim\CmsAclModule\Http\Requests\Api;

use Czim\CmsAclModule\Http\Requests\Request;

class CreateRoleRequest extends Request
{

    public function rules()
    {
        return [
            'key'           => 'required|string',
            'name'          => 'string',
            'permissions'   => 'array',
            'permissions.*' => 'string',
        ];
    }

}
