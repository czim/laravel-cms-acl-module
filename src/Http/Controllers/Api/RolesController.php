<?php
namespace Czim\CmsAclModule\Http\Controllers\Api;

use Czim\CmsAclModule\Http\Controllers\RolesController as WebRolesController;

class RolesController extends WebRolesController
{

    /**
     * @param $data
     * @return mixed
     */
    protected function indexResponse($data)
    {
        return $this->core->api()->response($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function showResponse($data)
    {
        return $this->core->api()->response($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function createResponse($data)
    {
        return $this->core->api()->response($data, 201);
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function updateResponse($data)
    {
        return $this->core->api()->response($data);
    }

    /**
     * @return mixed
     */
    protected function deleteResponse()
    {
        return $this->core->api()->response('OK');
    }

}
