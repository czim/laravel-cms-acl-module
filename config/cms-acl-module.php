<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ACL Views
    |--------------------------------------------------------------------------
    |
    | The views that the ACL module web controllers should use for each action.
    |
    */

    'views' => [

        'users' => [
            'index'  => 'cms::acl.users.index',
            'show'   => 'cms::acl.users.show',
            'create' => 'cms::acl.users.create',
            'edit'   => 'cms::acl.users.edit',
            'delete' => 'cms::acl.users.delete',
        ],

        'roles' => [
            'index'  => 'cms::acl.roles.index',
            'show'   => 'cms::acl.roles.show',
            'create' => 'cms::acl.roles.create',
            'edit'   => 'cms::acl.roles.edit',
            'delete' => 'cms::acl.roles.delete',
        ],
    ],

];
