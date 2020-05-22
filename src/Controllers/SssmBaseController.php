<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: SssmBaseController.php
 *  Date: 2020/04/21 17:37
 *  Author: kohenji
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Controllers;

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

use App\Models\User;
use CI4Smarty\ThirdParty\CiSmarty;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use sssm\Config\SssmThemes;
use Exception;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use SmartyException;

/**
 * Class BaseController
 * @package App\Controllers
 * @property \Smarty $smarty
 * @property Services session
 * @property SssmThemes sssm
 * @property User user
 */
class SssmBaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
    protected $smarty;
    public $sssm;
    public $session;
    public $user;
    public $systemErrorMessage;
    public $systemMessage;

    /**
     * Constructor.
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
	public function initController( RequestInterface $request, ResponseInterface $response, LoggerInterface $logger){
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
        $this->session = Services::session();
        $this->smarty = new CiSmarty();
        $this->user = new User();
	}

	public function view( $method ){
        try{
            $ext = $_ENV['CI4Smarty.DefaultTemplateExtension'] ?? '.tpl';
            list( $class , $file ) = explode( "::" , $method );
            if( substr( $file , 0 , -strlen( $ext ) ) != $ext ){
                $file .= $ext;
            }

            if( class_exists( $class ) ){
                $dir = ( new ReflectionClass( $class ) )->getShortName();
            }else{
                $dir = $class;
            }
            $this->smarty->assign( 'CI' , $this );
            $this->sssm->include_file['body'] = $dir . DIRECTORY_SEPARATOR . $file;
            $tpl = $this->sssm->systemName . DIRECTORY_SEPARATOR . $this->sssm->theme_dir_name . DIRECTORY_SEPARATOR . $this->sssm->theme . DIRECTORY_SEPARATOR . $this->sssm->theme_tpl_file_name;

            return $this->smarty->display( $tpl );
        }catch( SmartyException $e ){
            die( 'Smarty error : ' . $e->getMessage() . ( CI_DEBUG ? " at " . $e->getFile() . " : " . $e->getLine() : '' ) );
        }catch( Exception $e ){
            die( 'System error : ' . $e->getMessage() . ( CI_DEBUG ? " at " . $e->getFile() . " : " . $e->getLine() : '' ) );
        }
    }
}
