<?php
namespace Czim\CmsAclModule\Http\Controllers\Api;

use Czim\CmsAclModule\Api\Transformers\RoleTransformer;
use Czim\CmsAclModule\Http\Controllers\RolesController as WebRolesController;
use Czim\CmsCore\Api\Response\TransformContainer;

class RolesController extends WebRolesController
{

    /**
     * @param $data
     * @return mixed
     */
    protected function indexResponse($data)
    {
        return $this->core->api()->response(
            $this->makeContainer($data)
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function showResponse($data)
    {
        return $this->core->api()->response(
            $this->makeContainer($data, false)
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function createResponse($data)
    {
        return $this->core->api()->response(
            $this->makeContainer($data, false),
            201
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function updateResponse($data)
    {
        return $this->core->api()->response(
            $this->makeContainer($data, false)
        );
    }

    /**
     * @return mixed
     */
    protected function deleteResponse()
    {
        return $this->core->api()->response('OK', 204);
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
            'transformer' => new RoleTransformer,
            'collection'  => $collection,
        ]);
    }
}
