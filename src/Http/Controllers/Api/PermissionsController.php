<?php
namespace Czim\CmsAclModule\Http\Controllers\Api;

use Czim\CmsAclModule\Api\Transformers\PermissionTransformer;
use Czim\CmsAuth\Http\Controllers\Controller;
use Czim\CmsCore\Api\Response\TransformContainer;
use Czim\CmsCore\Contracts\Core\CoreInterface;

class PermissionsController extends Controller
{

    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @param CoreInterface    $core
     */
    public function __construct(CoreInterface $core)
    {
        $this->core = $core;

        $this->core->acl()->initialize();
    }


    /**
     * Returns available permissions as defined by loaded modules.
     *
     * @return mixed
     */
    public function available()
    {
        return $this->core->api()->response(
            $this->makeContainer(
                $this->core->modules()->getAllPermissions()
            )
        );
    }

    /**
     * Returns available permissions for a single module.
     *
     * @param $key
     * @return mixed
     */
    public function module($key)
    {
        if ( ! $this->core->modules()->has($key)) {
            abort(404, 'Module not loaded');
        }

        return $this->core->api()->response(
            $this->makeContainer(
                $this->core->modules()->getModulePermissions($key)
            )
        );
    }

    /**
     * Returns the permissions actually in use by users and roles.
     *
     * @return mixed
     */
    public function used()
    {
        return $this->core->api()->response(
            $this->makeContainer(
                $this->core->auth()->getAllPermissions()
            )
        );
    }

    /**
     * Wraps data in a transform container.
     *
     * @param array $data
     * @param bool  $collection
     * @return TransformContainer
     */
    protected function makeContainer($data, $collection = true)
    {
        return new TransformContainer([
            'content'     => $data,
            'transformer' => new PermissionTransformer,
            'collection'  => $collection,
        ]);
    }
}
