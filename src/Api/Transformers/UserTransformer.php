<?php
namespace Czim\CmsAclModule\Api\Transformers;

use Czim\CmsCore\Contracts\Auth\UserInterface;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * @param UserInterface|Model $user
     * @return array
     */
    public function transform(UserInterface $user)
    {
        return [
            'id'          => $user->id,
            'email'       => $user->email,
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'roles'       => $user->all_roles,
            'permissions' => $user->all_permissions,
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => cms()->apiRoute('acl.users.show', [$user->id]),
                ],
            ],
        ];
    }

}
