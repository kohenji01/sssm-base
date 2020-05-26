<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: SssmUser.php
 *  Date: 2020/05/25 18:07
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Config;

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