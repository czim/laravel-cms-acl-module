<?php
namespace Czim\CmsAclModule\Http\Controllers;

use Czim\CmsCore\Contracts\Auth\AuthenticatorInterface;
use Czim\CmsCore\Contracts\Core\CoreInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @var AuthenticatorInterface
     */
    protected $auth;

    /**
     * @param CoreInterface          $core
     * @param AuthenticatorInterface $auth
     */
    public function __construct(CoreInterface $core, AuthenticatorInterface $auth)
    {
        $this->core = $core;
        $this->auth = $auth;
    }

}
