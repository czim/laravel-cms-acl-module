<?php
namespace Czim\CmsAclModule\Http\Requests\Api;

use Czim\CmsAclModule\Http\Requests\Request;

class UpdateRoleRequest extends Request
{

    public function rules()
    {
        return [
            'name'          => 'string',
            'permissions'   => 'array',
            'permissions.*' => 'string',
        ];
    }
    
}
