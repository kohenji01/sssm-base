<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: Index.php
 *  Date: 2020/04/21 16:53
 *  Author: kohenji
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Controllers\Admin;

use Sssm\Controllers\AdminBaseController;

/**
 * Class Index
 */
class Index extends AdminBaseController{

    public function index(){
        echo "admin top";
    }


    public function test(){
		echo "admin test";
	}

	public function login(){
        echo 'loginpage';
    }

	//--------------------------------------------------------------------

}
