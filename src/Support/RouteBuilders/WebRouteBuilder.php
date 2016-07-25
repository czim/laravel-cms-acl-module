<?php
namespace Czim\CmsAclModule\Support\RouteBuilders;

use Illuminate\Routing\Router;

class WebRouteBuilder
{

    /**
     * @param Router $router
     */
    public function buildUserRoutes(Router $router)
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
                    'middleware' => [cms_mw_permission('acl.users.store')],
                    'uses'       => 'UsersController@store',
                ]);

                $router->get('/{key}', [
                    'as'   => 'show',
                    'uses' => 'UsersController@show',
                ]);

                $router->put('{key}', [
                    'as'         => 'update',
                    'middleware' => [cms_mw_permission('acl.users.update')],
                    'uses'       => 'UsersController@update',
                ]);

                $router->delete('{key}', [
                    'as'         => 'destroy',
                    'middleware' => [cms_mw_permission('acl.users.destroy')],
                    'uses'       => 'UsersController@destroy',
                ]);
            }
        );
    }

    /**
     * @param Router $router
     */
    public function buildRoleRoutes(Router $router)
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
                    'middleware' => [cms_mw_permission('acl.roles.store')],
                    'uses'       => 'RolesController@store',
                ]);

                $router->get('/{key}', [
                    'as'   => 'show',
                    'uses' => 'RolesController@show',
                ]);

                $router->put('{key}', [
                    'as'         => 'update',
                    'middleware' => [cms_mw_permission('acl.roles.update')],
                    'uses'       => 'RolesController@update',
                ]);

                $router->delete('{key}', [
                    'as'         => 'destroy',
                    'middleware' => [cms_mw_permission('acl.roles.destroy')],
                    'uses'       => 'RolesController@destroy',
                ]);
            }
        );
    }

}
