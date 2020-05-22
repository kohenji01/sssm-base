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

namespace sssm\Config;

class SssmUser extends SssmThemes{

    // ユーザ用のテーマ
    public $theme = 'default';

    public $interface;

    public function __construct(){
        parent::__construct();

        $this->interface = (object)[
            'hasNavbarUsername' => true ,               //ユーザ名を表示する場合 true
            'hasNavbarLocale'   => true ,               //言語選択を表示する場合 true
            'hasNavbarMessage'  => true ,               //メッセージを表示する場合 true
            'hasNavbarProfile'  => true ,               //プロフィールを表示する場合 true
        ];

    }



}