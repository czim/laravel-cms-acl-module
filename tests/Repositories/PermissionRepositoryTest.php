<?php
namespace Czim\CmsAclModule\Test\Repositories;

use Czim\CmsAclModule\Repositories\PermissionRepository;
use Czim\CmsAclModule\Test\TestCase;
use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;
use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Contracts\Modules\ModuleManagerInterface;
use Illuminate\Support\Collection;

class PermissionRepositoryTest extends TestCase
{

    /**
     * Testing menu groups config.
     *
     * @var array
     */
    protected $menuGroupsConfig = [];

    /**
     * Testing menu modules config.
     *
     * @var array
     */
    protected $menuModulesConfig = [];

    /**
     * @var Collection
     */
    protected $modules;


    /**
     * @test
     */
    function it_returns_all_permissions_available()
    {
        $permissions = [
            'some.permission',
            'another.permission',
            'and.a.third.permission',
            'some.custom.permission',
            'another.custom.permission',
        ];

        $mock = $this->getMockModuleManager();

        $mock->expects($this->once())
            ->method('getAllPermissions')
            ->willReturn($permissions);

        $repository = new PermissionRepository($this->getMockCore(), $this->getMockAuth(), $mock);

        $this->assertEquals($permissions, $repository->getAll());
    }

    /**
     * @test
     */
    function it_returns_all_permissions_actually_assigned()
    {
        $permissions = [
            'permission.in.use.a',
            'permission.in.use.b',
        ];

        $repository = $this->makePermissionRepository();

        $this->assertEquals($permissions, $repository->getAllInUse());
    }

    /**
     * @test
     */
    function it_returns_all_custom_configured_permissions()
    {
        $permissions = [
            'some.custom.permission',
            'another.custom.permission',
        ];

        $repository = $this->makePermissionRepository();

        $this->assertEquals($permissions, $repository->getCustom());
    }



    /**
     * @return PermissionRepository
     */
    protected function makePermissionRepository()
    {
        return new PermissionRepository($this->getMockCore(), $this->getMockAuth(), $this->getMockModuleManager());
    }


    // ------------------------------------------------------------------------------
    //      Helpers
    // ------------------------------------------------------------------------------

    /**
     * @return CoreInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCore()
    {
        $mock = $this->getMockBuilder(CoreInterface::class)->getMock();

        $mock->method('modules')
            ->willReturn($this->getMockModuleManager($this->modules));

        $mock->method('moduleConfig')
            ->willReturnCallback(function ($key) {
                switch ($key) {
                    case 'menu.groups':
                        return $this->menuGroupsConfig;

                    case 'menu.modules':
                        return $this->menuModulesConfig;
                }

                return null;
            });

        return $mock;
    }

    /**
     * @return AuthenticatorInterface
     */
    protected function getMockAuth()
    {
        return app(AuthenticatorInterface::class);
    }

    /**
     * @param null|Collection $modules
     * @return ModuleManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockModuleManager($modules = null)
    {
        $mock = $this->getMockBuilder(ModuleManagerInterface::class)->getMock();

        $modules = $modules ?: new Collection;

        $mock->method('getModules')
             ->willReturn($modules);

        return $mock;
    }

}
