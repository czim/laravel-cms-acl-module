<?php
namespace Czim\CmsAclModule\Api\Transformers;

use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{

    /**
     * @param $permission
     * @return array
     */
    public function transform($permission)
    {
        return [
            'key' => $permission,
        ];
    }

}
