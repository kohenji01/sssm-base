<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: AdminBaseController.php
 *  Date: 2020/04/21 16:53
 *  Author: kohenji
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Router\Exceptions\RedirectException;
use Sssm\Base\Config\SssmAdmin;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 * @package App\Controllers
 * @property SssmAdmin sssm
 */
class AdminBaseController extends SssmBaseController{

    /** @noinspection PhpUnhandledExceptionInspection */
    public function initController( RequestInterface $request , ResponseInterface $response , LoggerInterface $logger ){
        parent::initController( $request , $response , $logger );
        $this->sssm = new SssmAdmin();
        if( !isset( $_SESSION[$this->sssm->systemName]['User']['role'] ) ||
            !$this->sssm->hasRole( $_SESSION[$this->sssm->systemName]['User']['role'] ) ){
            throw new RedirectException( '/Auth' );
        }
    }

}
