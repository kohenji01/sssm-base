<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: Cli.php
 *  Date: 2020/06/05 16:38
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Controllers;

/** @noinspection PhpUnused */
class Cli extends UserBaseController{

    public $CI_PATH= '';
    
    public function __construct(){
        if( isset( $_ENV['sssm.ci_index_php'] ) && $_ENV['sssm.ci_index_php'] != '' ){
            $this->CI_PATH = $_ENV['sssm.ci_index_php'];
        }else{
            $this->CI_PATH = dirname( APPPATH ) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
        }
    }
    
    public function index( $function = '' , $action = '' , $g_params = '' , $p_params = '' ){
        if( $function == '' || $action == '' || !is_cli() ){
            die();
        }

        if( $g_params != '' ){
            $_GET = unserialize( urldecode( $g_params ) );
        }
    
        if( $p_params != '' ){
            $_POST = unserialize( urldecode( $p_params ) );
        }
    
        $class = str_replace( '.' , '\\' , $function );
        $API = new $class();
        if( $API->apiEnable === true && in_array( $action , $API->apiExecutable ) ){
            $API->accessFromAPI = true;
            $ret = $API->$action();
        }else{
            die();
        }
        
        return $ret;
    }
    
}