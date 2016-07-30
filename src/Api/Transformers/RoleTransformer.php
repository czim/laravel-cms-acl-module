<?php
namespace Czim\CmsAclModule\Api\Transformers;

use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{

    /**
     * @param object $role
     * @return array
     */
    public function transform($role)
    {
        return [
            'key'         => $role->key,
            'permissions' => $role->permissions,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => cms()->apiRoute('acl.roles.show', [ $role->key ]),
                ]
            ],
        ];
    }

}
