<?php
namespace Czim\CmsAclModule\Http\Requests;

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
