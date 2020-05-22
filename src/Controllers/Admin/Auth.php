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

use Sssm\Controllers\AuthBaseController;

/**
 * Class Index
 */
class Auth extends AuthBaseController{

    public function index(){
        echo "admin login top";
    }


    public function test(){
		echo "admin test";
	}

	public function login(){
        echo 'loginpage';
    }

	//--------------------------------------------------------------------

}
