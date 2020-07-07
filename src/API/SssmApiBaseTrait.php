<?php
/** @noinspection PhpUnused */
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: SssmApiBase.php
 *  Date: 2020/06/11 15:15
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\API;

trait SssmApiBaseTrait{
    /**
     * @var bool $apiEnable APIが有効かどうか？
     */
    protected $apiEnable = true;
    /**
     * @var array $apiExecutable APIが使用できるメソッド
     */
    protected $apiExecutable = [];
    /**
     * @var bool $accessFromAPI APIからアクセスされると自動的にこれはTrueになる
     */
    protected $accessFromAPI = false;
    /**
     * @var string $apiOutputType APIの出力方法（json|xml）
     */
    protected $apiOutputType = 'json';
    /**
     * @var array $apiBackgroundExecutable バックグラウンド動作可能なメソッド
     */
    protected $apiBackGroundExecutable = [];
    
    /**
     * APIが有効かどうか？
     * @param bool $flag
     */
    protected function setApiEnable( bool $flag ){
        $this->apiEnable = $flag;
    }
    
    /**
     * APIが使用できるメソッド
     * @param array $list
     */
    protected function setApiExecutable( array $list ){
        $this->apiExecutable = $list;
    }
    
    /**
     * APIからアクセスされると自動的にこれはTrueになる
     * @param bool $flag
     */
    public function setAccessFromAPI( bool $flag ){
        $this->accessFromAPI = $flag;
    }
    
    /**
     * APIの出力方法（json|xml）
     * @param string $type
     */
    protected function setApiOutputType( string $type ){
        $this->apiOutputType = $type;
    }
    
    /**
     * バックグラウンド動作可能なメソッド
     * @param array $list
     */
    protected function setApiBackgroundExecutable( array $list ){
        $this->apiBackGroundExecutable = $list;
    }

}