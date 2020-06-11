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

trait SssmApiBase{
    /**
     * @var bool $apiEnable APIが有効かどうか？
     */
    public $apiEnable = true;
    /**
     * @var array $apiExecutable APIが使用できるメソッド
     */
    public $apiExecutable = [];
    /**
     * @var bool $accessFromAPI APIからアクセスされると自動的にこれはTrueになる
     */
    public $accessFromAPI = false;
    /**
     * @var string $apiOutputType APIの出力方法（json|xml）
     */
    public $apiOutputType = 'json';
    /**
     * @var array $apiBackgroundExecutable バックグラウンド動作可能なメソッド
     */
    public $apiBackGroundExecutable = [];
    
}