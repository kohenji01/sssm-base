<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: Auth.php
 *  Date: 2020/04/21 16:53
 *  Author: kohenji
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Controllers;

use App\Models\User;
use CodeIgniter\Router\Exceptions\RedirectException;
use Exception;

/**
 * Class Auth
 * @package App\Controllers
 * @property User user
 * @property \Smarty smarty
 */
class Auth extends AuthBaseController{

    public function index(){
        $this->_check_cond();
        $this->view( __METHOD__ );
    }

    public function login(){
        try{
            $this->_check_cond();
            $user = new User();
            $user->login_id = $_POST['login_id'] ?? '';
            $user->login_pw = $_POST['login_pw'] ?? '';

            $user->userLogin();
        }catch( Exception $e ){
            if( $e->getCode() > 900000 ){
                $this->systemErrorMessage = $e->getMessage();
                return $this->view( 'Auth::index' );
            }
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $e;
        }
        return redirect()->route( '/' );
    }

    public function logout(){
        $this->user->userLogout();
        return redirect()->route( '/' );
    }

    private function _check_cond(){
        if( isset( $_SESSION[$this->sssm->systemName]['User']) ){
            if( $_SESSION[$this->sssm->systemName]['User']['redirect_to'] != '' ){
                throw new RedirectException( $_SESSION[$this->sssm->systemName]['User']['redirect_to'] );
            }else{
                throw new RedirectException( '/' );
            }
        }
    }
    //--------------------------------------------------------------------

}
