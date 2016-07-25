<?php
namespace Czim\CmsAclModule\Http\Requests;

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
