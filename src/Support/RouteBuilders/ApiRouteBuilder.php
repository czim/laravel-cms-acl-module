<?php
namespace Czim\CmsAclModule\Support\RouteBuilders;

use Illuminate\Routing\Router;

class ApiRouteBuilder
{

    /**
     * @param Router $router
     */
    public function buildRoutes(Router $router)
    {
        $router->group(
            [
                'as'        => 'acl.',
                'prefix'    => 'acl',
                'namespace' => '\\Czim\\CmsAclModule\\Http\\Controllers\\Api',
            ],
            function (Router $router)  {

                $this->buildUserRoutes($router)
                     ->buildRoleRoutes($router);
            }
        );
    }

    /**
     * @param Router $router
     * @return $this
     */
    protected function buildUserRoutes(Router $router)
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
    protected function buildRoleRoutes(Router $router)
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

}
