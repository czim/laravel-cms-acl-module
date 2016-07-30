<?php
namespace Czim\CmsAclModule\Support\Route;

use Illuminate\Routing\Router;

class ApiRouteMapper
{

    /**
     * @param Router $router
     */
    public function mapRoutes(Router $router)
    {
        $router->group(
            [
                'as'        => 'acl.',
                'prefix'    => 'acl',
                'namespace' => '\\Czim\\CmsAclModule\\Http\\Controllers\\Api',
            ],
            function (Router $router)  {

                $this->mapUserRoutes($router)
                     ->mapRoleRoutes($router)
                     ->mapPermissionRoutes($router);
            }
        );
    }

    /**
     * @param Router $router
     * @return $this
     */
    protected function mapUserRoutes(Router $router)
    {
        $router->group(
            [
                'as'         => 'users.',
                'prefix'     => 'users',
                'middleware' => [cms_mw_permission('acl.users.*')],
            ],
            function (Router $router) {

                $router->get('/', [
                    'as'   => 'index',
                    'uses' => 'UsersController@index',
                ]);

                $router->post('/', [
                    'as'         => 'store',
                    'middleware' => [cms_mw_permission('acl.users.create')],
                    'uses'       => 'UsersController@store',
                ]);

                $router->get('/{key}', [
                    'as'   => 'show',
                    'uses' => 'UsersController@show',
                ]);

                $router->put('{key}', [
                    'as'         => 'update',
                    'middleware' => [cms_mw_permission('acl.users.edit')],
                    'uses'       => 'UsersController@update',
                ]);

                $router->delete('{key}', [
                    'as'         => 'destroy',
                    'middleware' => [cms_mw_permission('acl.users.delete')],
                    'uses'       => 'UsersController@destroy',
                ]);
            }
        );

        return $this;
    }

    /**
     * @param Router $router
     * @return $this
     */
    protected function mapRoleRoutes(Router $router)
    {
        $router->group(
            [
                'as'         => 'roles.',
                'prefix'     => 'roles',
                'middleware' => [cms_mw_permission('acl.roles.*')],
            ],
            function (Router $router) {

                $router->get('/', [
                    'as'   => 'index',
                    'uses' => 'RolesController@index',
                ]);

                $router->post('/', [
                    'as'         => 'store',
                    'middleware' => [cms_mw_permission('acl.roles.create')],
                    'uses'       => 'RolesController@store',
                ]);

                $router->get('/{key}', [
                    'as'   => 'show',
                    'uses' => 'RolesController@show',
                ]);

                $router->put('{key}', [
                    'as'         => 'update',
                    'middleware' => [cms_mw_permission('acl.roles.edit')],
                    'uses'       => 'RolesController@update',
                ]);

                $router->delete('{key}', [
                    'as'         => 'destroy',
                    'middleware' => [cms_mw_permission('acl.roles.delete')],
                    'uses'       => 'RolesController@destroy',
                ]);
            }
        );

        return $this;
    }

    /**
     * @param Router $router
     * @return $this
     */
    protected function mapPermissionRoutes(Router $router)
    {
        $router->group(
            [
                'as'         => 'permissions.',
                'prefix'     => 'permissions',
                'middleware' => [cms_mw_permission('acl.roles.*')],
            ],
            function (Router $router) {

                $router->get('/', [
                    'as'   => 'index',
                    'uses' => 'PermissionsController@available',
                ]);

                $router->get('module/{key}', [
                    'as'   => 'module',
                    'uses' => 'PermissionsController@module',
                ]);

                $router->get('used', [
                    'as'   => 'used',
                    'uses' => 'PermissionsController@used',
                ]);
            }
        );

        return $this;
    }

}
