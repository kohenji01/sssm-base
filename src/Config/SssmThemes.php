<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: SssmThemes.php
 *  Date: 2020/05/25 18:08
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Config;

/**
 * SSSMテーマ設定クラス
 * Class SssmThemes
 * @package Config
 */
class SssmThemes extends SssmLang{

    // 設定した変数名がviews/themes/ディレクトリ内の各テーマの名称と一致する
    public $theme;

    public $include_file = [
        'head' => '' ,
        'pre_header' => '' ,
        'header' => '' ,
        'body' => '' ,
        'footer' => '' ,
        'suf_footer' => '' ,
    ];

    public $content = [
        'robots' => '' ,
        'keyword' => '' ,
        'description' => '' ,
        'title' => '' ,
    ];

    public $theme_dir_name = 'themes';
    public $theme_tpl_file_name = 'index.tpl';

    public $message;
    public $code;

    public $interface;

    public function __construct(){
        parent::__construct();
    }

}