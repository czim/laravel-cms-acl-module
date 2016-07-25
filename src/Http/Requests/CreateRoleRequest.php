<?php
namespace Czim\CmsAclModule\Http\Requests;

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
