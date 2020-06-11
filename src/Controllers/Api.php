<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: Api.php
 *  Date: 2020/06/11 15:20
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

/** @noinspection PhpUnused */
class Api extends UserBaseController{

    public $CI_PATH= '';
    
    public function __construct(){
        if( isset( $_ENV['sssm.ci_index_php'] ) && $_ENV['sssm.ci_index_php'] != '' ){
            $this->CI_PATH = $_ENV['sssm.ci_index_php'];
        }else{
            $this->CI_PATH = dirname( APPPATH ) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
        }
    }
    
    public function index( $function = '' , $action = '' , $background = '' ){
        try{
            $API = null;
            $ret = true;
            if( $function == '' || $action == '' ){
                throw PageNotFoundException::forPageNotFound();
            }
            $class = str_replace( '.' , '\\' , $function );
            $API = new $class();
            if( $API->apiEnable === true && in_array( $action , $API->apiExecutable ) ){
                $API->accessFromAPI = true;
                if( $background == 'background' && in_array( $action , $API->apiBackGroundExecutable ) ){
                    $g_params = urlencode( serialize( $this->request->getGet() ) );
                    $p_params = urlencode( serialize( $this->request->getPost() ) );
                    $cmd = 'nohup php ' . $this->CI_PATH . " Cli {$function} {$action} \"{$g_params}\" \"{$p_params}\" > /dev/null &";
                    exec( $cmd );
                }else{
                    $ret = $API->$action();
                }
            }else{
                throw PageNotFoundException::forPageNotFound();
            }
    
        }catch( PageNotFoundException $e ){
            throw $e;
        }catch( Exception $e ){
            $ret = false;
        }
        
        switch( $API->apiOutputType ){
            case 'xml':
                $this->_xmlOutput( $ret );
                break;
            case 'json':
            default:
                $this->_jsonOutput( $ret );
                break;
        }
        
        return;
    }
    
    private function _jsonOutput( $contents ){
        header( "Content-Type: application/json; charset=utf-8" );
        echo json_encode( $contents );
    }
    
    private function _xmlOutput( $contents ){
        header( "Content-Type: text/xml" );
        header( "Content-Disposition: inline" );
        echo $contents;
    }
}