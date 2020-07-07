<?php
/** @noinspection PhpUnused */
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: SssmBase.php
 *  Date: 2020/06/14 14:14
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\Config;

use CodeIgniter\Config\BaseConfig;

class SssmBase extends BaseConfig{
    
    public $systemMessage;
    
    public $systemErrorMessage;
    
    public $smartyBodyPath = '';
    
    /**
     * @var string システム名
     */
    public $systemName = '';
    
    public const systemRole_ROOT            = 1;
    public const systemRole_ADMINISTRATOR   = 2;
    public const systemRole_EDITOR          = 4;
    public const systemRole_AUTHOR          = 8;
    public const systemRole_CONTRIBUTER     = 16;
    public const systemRole_SUBSCRIBER      = 32;
    
    public function __construct(){
        parent::__construct();
        $this->systemName = $_ENV['sssm.sysname'] ?? 'sssm';
        
    }
    
    static public function hasRole( $targetRole , $yourRole = null ){
        if( $yourRole === null ){
            $yourRole = $_SESSION[($_ENV['sssm.sysname'] ?? 'sssm')]['User']['Role'];
        }
        if( $yourRole & $targetRole ){
            return true;
        }else{
            return false;
        }
    }
    
}