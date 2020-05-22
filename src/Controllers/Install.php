<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: Install.php
 *  Date: 2020/05/20 14:18
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Controllers;


use App\Models\SystemInit;
use Exception;

class Install extends UserBaseController{
    
    public $data=[];
    
    public function __construct(){
        try{
            if( file_exists( WRITEPATH . 'sssm_was_installed' ) ){
                throw new Exception( 'sssm installer is already executed. If you want to run installer again, You should erase ' . WRITEPATH . 'sssm_was_installed file and reload this page.' );
            }
            
            if( !file_exists( ROOTPATH . '.env' ) ){
                $url = "http" . ( $_SERVER['HTTPS'] ? 's' : '' ) . "://" . $_SERVER['SERVER_NAME'] . "/";
                file_put_contents( ROOTPATH . '.env' , <<<__EOF__
# Environment params build by sssm.

#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------

CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# sssm system config
#--------------------------------------------------------------------

# このCMSのシステム名（セッション変数の識別子などに使用されます）
sssm.sysname = 'sssm'

# ログインIDの種類（バリデーション）
# 使用できるパラメータなどは
# https://codeigniter4.github.io/userguide/libraries/validation.html#available-rules
# 参照のこと。
# 例えばログインIDにメールアドレスを使用したい場合は「valid_email」を加えて置くと
# 登録時に自動的に検証されるので便利です。
sssm.login_id_validation = 'required|valid_email|is_unique[users.login_id]'

# ログインPWの制限（バリデーション）
# default = 'required|min_length[8]'
sssm.login_pw_validation = 'required|min_length[8]'

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = '{$url}'
app.indexPage=''

__EOF__
                );
                header( "Location: {$url}Install" );
            }
            
        }catch( Exception $e ){
            die( $e->getMessage() );
        }
        $this->data['site_url'] = site_url();
    }
    
    public function index(){
        $this->smarty->assign( 'DATA' , $this->data );
        return $this->view( __METHOD__ );
    }

    public function checkenv(){
        $install = new SystemInit();
        $install->run();
        $this->data['checkResult'] = $install->checkResult;
        $this->smarty->assign( 'DATA' , $this->data );
        return $this->view( __METHOD__ );
        
    }
    
    public function execute(){
    
    }
}