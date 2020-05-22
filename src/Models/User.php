<?php
/**
 * =============================================================================================
 *  $URL:$
 *  $Rev:$
 *  $Date::                           $
 *  $Author:$
 *  $Id:$
 *  Copyright (c) 2019. SarahSystems lpc. All rights reserved.
 * =============================================================================================
 */

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Router\Exceptions\RedirectException;
use Config\SssmLang;
use Config\SssmUser;
use DateTime;
use Exception;
use function sssm\functions\check_ip;

/**
 * Class User
 * @package App\Models
 * @property SssmUser sssm
 */
class User extends Model{
    protected $table = 'users';
    protected $primaryKey = 'sid';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [ 'login_id' , 'password_hash' , 'last_logged_in_at' , 'enabled_at' , 'expired_at' , 'active' ];

    protected $useTimestamps = true ;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules    = [
        'login_id'      => 'required|valid_email' ,
        'login_pw'      => 'required|min_length[8]' ,
    ];
    protected $validationMessages;
    protected $skipValidation     = false;

    public $login_id;
    public $login_pw;
    public $enabled_at = [ 'data' => 'CURRENT_TIMESTAMP' , 'escape' => false ];
    public $expired_at = '2038-01-10 00:00:00';
    public    $active = 1;
    protected $sssm;
    protected $isAdmin = false;

    public $resultMessage = '';
    public $resultCode = 0;

    /**
     * User constructor.
     */
    public function __construct(){
        parent::__construct();

        $this->sssm = new SssmUser();

        // 環境変数でvalidationが設定されている場合はそちらを優先する
        if( !empty( $_ENV['sssm.login_id_validation'] ) ){
            $this->validationRules['login_id'] = $_ENV['sssm.login_id_validation'];
        }

        if( !empty( $_ENV['sssm.login_pw_validation'] ) ){
            $this->validationRules['login_pw'] = $_ENV['sssm.login_pw_validation'];
        }

        $this->validationMessages = [
            'login_id'      => [
                'is_unique'     => $this->sssm->systemErrorMessage[SssmLang::USER_CAN_NOT_USE_THIS_LOGIN_ID] ,
                'required'      => $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_ID_IS_REQUIRED] ,
            ] ,
            'login_pw'      => [
                'required'      => $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_PW_IS_REQUIRED] ,
            ] ,
        ];

    }


    /**
     * ユーザログイン
     * @return bool
     * @throws Exception
     */
    public function userLogin(){
        try{
            $builder = $this->builder();
            $builder->where( [ 'login_id' => $this->login_id , 'active' => 1 ,
            ] );
            $result = $this->find();

            if( !$result ){
                throw new Exception( $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_ID_IS_NOT_EXISTS] , SssmLang::USER_LOGIN_ID_IS_NOT_EXISTS );
            }else{
                $result = $result[0];
            }

            $this->checkUserStillValid( $result );
            $this->startSession( $result );

            if( $result['redirect_to'] != '' ){
                throw new RedirectException( $result['redirect_to'] );
            }

        }catch( Exception $e ){
            $this->setResult( $e->getCode() );
            throw $e;
        }

        return true;
    }

    /** @noinspection PhpUnused */
    public function userLogout(){
        try{
            $this->destroySession();
            $this->setResult( SssmLang::USER_WAS_LOGGED_OUT );
        }catch( Exception $e ){
            $this->setResult( $e->getCode() );
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $e;
        }
    }

    protected function checkUserStillValid( $info ){
        try{

            if( !$info ){
                //USER_LOGIN_ERROR
                throw new Exception(
                    $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_ERROR] ,
                    SssmLang::USER_LOGIN_ERROR
                );
            }

            // 32bit のPHPでも2038年問題をクリアできるように
            // DateTime class & format で文字列にして比較する
            //（strtotimeは32bit INTなので使えない）
            $now_obj = new Datetime();
            $now = $now_obj->format( 'Ymdhis' );

            $enabled_at_obj = new Datetime( $info['enabled_at'] );
            $enabled_at = $enabled_at_obj->format( 'Ymdhis' );

            $expired_at_obj = new Datetime( $info['expired_at'] );
            $expired_at = $expired_at_obj->format( 'Ymdhis' );

            if( $enabled_at > $now ){
                throw new Exception(
                    $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_BEFORE_ENABLED] ,
                    SssmLang::USER_LOGIN_BEFORE_ENABLED
                );
            }

            if( $expired_at < $now ){
                throw new Exception(
                    $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_EXPIRED] ,
                    SssmLang::USER_LOGIN_EXPIRED
                );
            }

            if( !password_verify( $this->login_pw , $info['password_hash'] ) ){
                throw new Exception(
                    $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_PW_IS_NOT_MATCHED] ,
                    SssmLang::USER_LOGIN_PW_IS_NOT_MATCHED
                );
            }

            // 有効なIP？
            if( $info['allowed_ip_address'] != "" && !check_ip( $info['allowed_ip_address'] ) ){
                throw new Exception(
                    $this->sssm->systemErrorMessage[SssmLang::USER_LOGIN_PW_IS_NOT_MATCHED] ,
                    SssmLang::USER_LOGIN_PW_IS_NOT_MATCHED
                );
            }
        }catch( Exception $e ){
            $this->setResult( $e->getCode() );
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $e;
        }
    }

    protected function destroySession(){
        unset( $_SESSION[$this->sssm->systemName]['User'] );
    }

    protected function startSession( $info ){
        $_SESSION[$this->sssm->systemName]['User'] = [
            'uid'                   => $info['uid'] ,
            'login_id'              => $info['login_id'] ,
            'role'                  => $info['role'] ,
            'redirect_to'           => $info['redirect_to'] ,
            'allowed_ip_address'    => $info['allowed_ip_address'] ,
            'Since'                 => time() ,
            'enabled_at'            => $info['enabled_at'] ,
            'expired_at'            => $info['expired_at'] ,
            'last_checked'          => time() ,
        ];
    }

    /**
     * ユーザ作成
     * @return bool
     * @throws Exception
     */
    public function createUser(){
        try{
            $builder = $this->builder();
            $data = [
                'login_id'      => $this->login_id ,
                'password_hash' => password_hash( $this->login_pw , PASSWORD_DEFAULT ) ,
                'expired_at'    => $this->expired_at ,
                'active'        => $this->active ,
            ];

            if( is_array( $this->enabled_at ) ){
                if( !empty( $this->enabled_at['data'] ) && isset( $this->enabled_at['escape'] ) ){
                    $builder->set( 'enabled_at' , $this->enabled_at['data'] , $this->enabled_at['escape'] );
                }
            }else{
                $data['enabled_at'] = $this->enabled_at;
            }

            if( is_array( $this->expired_at ) ){
                if( !empty( $this->expired_at['data'] ) && isset( $this->expired_at['escape'] ) ){
                    $builder->set( 'expired_at' , $this->expired_at['data'] , $this->expired_at['escape'] );
                }
            }else{
                $data['expired_at'] = $this->expired_at;
            }

            $result = $this->save( $data );

        }catch( Exception $e ){
            switch( $this->getSQLstate() ){
                case 23000:
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $this->setResult( SssmLang::USER_DB_ERROR_DUPLICATE_KEY );
                    throw new Exception(
                        $this->sssm->systemErrorMessage[SssmLang::USER_DB_ERROR_DUPLICATE_KEY] ,
                        SssmLang::USER_DB_ERROR_DUPLICATE_KEY
                    );
                    break;
                default:
                    $this->setResult( $e->getCode() );
                    /** @noinspection PhpUnhandledExceptionInspection */
                    throw $e;
                    break;
            }

        }

        return $result;
    }

    public function getAllUsers(){
        return $this->findAll();
    }

    protected function getSQLstate(){
        $db_driver = strtolower( $_ENV['database.default.DBDriver'] );
        return $this->db->$db_driver->sqlstate;
    }

    private function setResult( $type ){
        $this->resultCode       = $type;
        $this->resultMessage    = $this->sssm->systemMessage[$type] ?? '';
    }

}