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

use CodeIgniter\Config\BaseConfig;

class SssmBase extends BaseConfig{

    public $systemMessage;

    public $systemErrorMessage;

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
        $this->systemName = $_ENV['sssm.sysname'];

    }

    public function hasRole( $role ){
        if( $role & self::systemRole_ROOT           ){ return true; }
        if( $role & self::systemRole_ADMINISTRATOR  ){ return true; }
        if( $role & self::systemRole_EDITOR         ){ return true; }
        if( $role & self::systemRole_AUTHOR         ){ return true; }
        if( $role & self::systemRole_CONTRIBUTER    ){ return true; }
        if( $role & self::systemRole_SUBSCRIBER     ){ return true; }

        return  false;
    }

}