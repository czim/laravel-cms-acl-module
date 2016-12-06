<?php
namespace Czim\CmsAclModule\Http\Controllers;

use Czim\CmsAclModule\Contracts\PermissionRepositoryInterface;
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
     * @var PermissionRepositoryInterface
     */
    protected $permissions;

    /**
     * @param CoreInterface                 $core
     * @param AuthenticatorInterface        $auth
     * @param PermissionRepositoryInterface $permissions
     */
    public function __construct(
        CoreInterface $core,
        AuthenticatorInterface $auth,
        PermissionRepositoryInterface $permissions
    ) {
        $this->core        = $core;
        $this->auth        = $auth;
        $this->permissions = $permissions;
    }

}
