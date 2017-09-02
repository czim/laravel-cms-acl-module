<?php
namespace Czim\CmsAclModule\Test;

use Illuminate\Contracts\Foundation\Application;
use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;
use Czim\CmsCore\Providers\CmsCoreServiceProvider;
use Czim\CmsCore\Support\Enums\Component;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    const USER_ADMIN_EMAIL    = 'admin@cms.com';
    const USER_ADMIN_PASSWORD = 'password';

    /**
     * Define environment setup.
     *
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Load the CMS even when unit testing
        $app['config']->set('cms-core.testing', true);

        // Set up service providers for tests, excluding what is not part of this package
        $app['config']->set('cms-core.providers', [
            \Czim\CmsCore\Providers\ModuleManagerServiceProvider::class,
            \Czim\CmsCore\Providers\LogServiceProvider::class,
            \Czim\CmsCore\Providers\RouteServiceProvider::class,
            \Czim\CmsCore\Providers\MiddlewareServiceProvider::class,
            \Czim\CmsCore\Providers\MigrationServiceProvider::class,
            \Czim\CmsCore\Providers\ViewServiceProvider::class,
            //\Czim\CmsAuth\Providers\CmsAuthServiceProvider::class,
            //\Czim\CmsTheme\Providers\CmsThemeServiceProvider::class,
            //\Czim\CmsAuth\Providers\Api\OAuthSetupServiceProvider::class,
            \Czim\CmsCore\Providers\Api\CmsCoreApiServiceProvider::class,
            \Czim\CmsCore\Providers\Api\ApiRouteServiceProvider::class,
        ]);

        $app['config']->set('cms-api.providers', []);

        // Mock component bindings in the config
        $app['config']->set(
            'cms-core.bindings', [
                Component::BOOTCHECKER => $this->getTestBootCheckerBinding(),
                Component::CACHE       => \Czim\CmsCore\Core\Cache::class,
                Component::CORE        => \Czim\CmsCore\Core\Core::class,
                Component::MODULES     => \Czim\CmsCore\Modules\ModuleManager::class,
                Component::API         => \Czim\CmsCore\Api\ApiCore::class,
                Component::ACL         => \Czim\CmsCore\Auth\AclRepository::class,
                Component::MENU        => \Czim\CmsCore\Menu\MenuRepository::class,
                Component::AUTH        => 'mock-cms-auth',
        ]);

        $app['config']->set('cms-acl-module.permissions', [
            'some.custom.permission',
            'another.custom.permission',
        ]);

        $this->mockBoundCoreExternalComponents($app);

        $app->register(CmsCoreServiceProvider::class);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * Sets up the database for testing. This includes migration and standard seeding.
     */
    protected function setUpDatabase()
    {
        $this->app['config']->set('cms-core.database.driver', 'testbench');

        $this->migrateDatabase()
             ->seedDatabase();
    }

    /**
     * @return $this
     */
    protected function migrateDatabase()
    {
        // Note that although this will set up the migrated tables with the
        // prefix set by the CMS config, this will NOT use the cms:migrate
        // artisan context, so the migrations table will not be prefixed.

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations/api'),
        ]);

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations/sentinel'),
        ]);

        return $this;
    }

    /**
     * Seeds the database with standard testing content.
     */
    protected function seedDatabase()
    {
    }

    /**
     * @return string
     */
    protected function getTestBootCheckerBinding()
    {
        return \Czim\CmsCore\Core\BootChecker::class;
    }

    /**
     * Mocks components that should not be part of any core test.
     *
     * @param Application $app
     * @return $this
     */
    protected function mockBoundCoreExternalComponents(Application $app)
    {
        $app->bind('mock-cms-auth', function () {

            $mock = $this->getMockBuilder(AuthenticatorInterface::class)->getMock();

            $mock->method('version')->willReturn('1.2.3');

            $mock->method('getRouteLoginAction')->willReturn('MockController@index');
            $mock->method('getRouteLoginPostAction')->willReturn('MockController@index');
            $mock->method('getRouteLogoutAction')->willReturn('MockController@index');

            $mock->method('getRoutePasswordEmailGetAction')->willReturn('MockController@index');
            $mock->method('getRoutePasswordEmailPostAction')->willReturn('MockController@index');
            $mock->method('getRoutePasswordResetGetAction')->willReturn('MockController@index');
            $mock->method('getRoutePasswordResetPostAction')->willReturn('MockController@index');

            $mock->method('getAllPermissions')->willReturn(['permission.in.use.a', 'permission.in.use.b']);

            return $mock;
        });

        return $this;
    }

}
