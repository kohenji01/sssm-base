<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: SssmLang.php
 *  Date: 2020/05/25 18:08
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Config;

use function Sssm\Helpers\change_locale;

class SssmLang extends SssmBase{

    public $enabledLanguage = [
        'en_US' ,
        'en_GB' ,
        'ja_JP' ,
        'zh_CN' ,
        'zh_TW' ,
        'ko_KR' ,
    ];

    public $defaultLocale = 'ja_JP';

    public $getTextDomain;

    public const SUCCESSFUL                         = 1000000;
    public const USER_WAS_LOGGED_OUT                = 1000001;
    
    public const FILE_NOT_FOUND                     = 8000000;
    public const FILE_PATH_IS_NOT_WRITABLE          = 8000001;
    
    public const USER_DB_ERROR_DUPLICATE_KEY        = 9000000;
    public const USER_CAN_NOT_USE_THIS_LOGIN_ID     = 9000001;
    public const USER_LOGIN_ID_IS_REQUIRED          = 9000002;
    public const USER_LOGIN_PW_IS_REQUIRED          = 9000003;
    public const USER_LOGIN_ID_IS_NOT_EXISTS        = 9000004;
    public const USER_LOGIN_PW_IS_NOT_MATCHED       = 9000005;
    public const USER_LOGIN_EXPIRED                 = 9000006;
    public const USER_LOGIN_BEFORE_ENABLED          = 9000007;
    public const USER_LOGIN_NOT_ALLOWED_IP_ADDR     = 9000008;
    public const USER_LOGIN_ERROR                   = 9000999;
    
    // Installer error
    public const INSTALLER_INVALID_PARAMS           = 9005000;
    
    
    public function __construct(){
        parent::__construct();

        $this->getTextDomain = $this->systemName;

        if( !isset( $_SESSION[($this->systemName)]['locale'] ) ){
            $_SESSION[($this->systemName)]['locale'] = $this->defaultLocale;
        }

        if( isset( $_GET['locale'] ) && in_array( $_GET['locale'] , $this->enabledLanguage )){
            $_SESSION[($this->systemName)]['locale'] = $_GET['locale'];
            helper( 'sssm' );
            change_locale( $_GET['locale'] , $this->getTextDomain );
        }


        $this->systemErrorMessage = [
            self::USER_DB_ERROR_DUPLICATE_KEY       => _( '登録済みユーザです' ) ,
            self::USER_CAN_NOT_USE_THIS_LOGIN_ID    => _( 'そのログインIDは使用できません' ) ,
            self::USER_LOGIN_ID_IS_REQUIRED         => _( 'ログインIDを入力してください' ) ,
            self::USER_LOGIN_PW_IS_REQUIRED         => _( 'ログインPWを入力してください' ) ,
            self::USER_LOGIN_ID_IS_NOT_EXISTS       => _( 'ログインIDまたはPWが正しくありません(ログインユーザ不在)' ) ,
            self::USER_LOGIN_PW_IS_NOT_MATCHED      => _( 'ログインIDまたはPWが正しくありません(パスワード不一致)' ) ,
            self::USER_LOGIN_EXPIRED                => _( 'ログインIDまたはPWが正しくありません(有効期限切れ)' ) ,
            self::USER_LOGIN_BEFORE_ENABLED         => _( 'ログインIDまたはPWが正しくありません(有効日時未達)' ) ,
            self::USER_LOGIN_NOT_ALLOWED_IP_ADDR    => _( 'ログインエラー（不許可IPアドレス）' ) ,
            self::USER_LOGIN_ERROR                  => _( 'ログインエラー' ) ,

            self::INSTALLER_INVALID_PARAMS          => _( 'インストーラに必要な情報が揃っていません' ) ,

            self::FILE_NOT_FOUND                    => _( 'ファイルが見つかりません' ) ,
            self::FILE_PATH_IS_NOT_WRITABLE         => _( 'その場所にファイルまたはディレクトリが書き込めません' ) ,

        ];

        $this->systemMessage = [
            self::SUCCESSFUL                        => _( '成功しました' ) ,
            self::USER_WAS_LOGGED_OUT               => _( 'ログアウトしました' ) ,
        ];

    }

}
