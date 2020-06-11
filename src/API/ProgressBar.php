<?php
/**
 * =============================================================================================
 *  Project: sssm-base
 *  File: ProgressBar.php
 *  Date: 2020/06/11 15:18
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\Base\API;

use CodeIgniter\Exceptions\PageNotFoundException;
use Sssm\Base\Config\SssmBase;

/** @noinspection PhpUnused */
class ProgressBar {
    
    use SssmApiBase;
    
    public $apiExecutable = [ 'getProgress' , 'startProgress' , 'test' ];
    
    protected $saveDirectory = '';
    protected $progressId = null;
    
    public $timeout = 3600; //sec.
    
    public $contents = [];
    
    public function __construct(){
        $config = new SssmBase();
        $this->saveDirectory = WRITEPATH . $config->systemName . DIRECTORY_SEPARATOR . 'ProgressBar' . DIRECTORY_SEPARATOR ;
        $this->progressId = $_GET['pid'] ?? '';
    }
    
    /** @noinspection PhpUnused */
    public function getProgress( $progressId = '' ){
        if( $progressId != '' ){
            $this->progressId = $progressId;
        }
        
        if( $this->progressId == '' || !file_exists( $this->saveDirectory . $this->progressId ) ){
            throw PageNotFoundException::forPageNotFound();
        }
    
        $this->contents = unserialize( file_get_contents( $this->saveDirectory . $this->progressId ) );
    
        $this->gc_progress();
    
        return $this->contents;
    }
    
    public function saveProgress( $data , $progressId = '' ){
    
        if( $progressId == '' ){
            $this->progressId = uniqid( '' , true );
        }else{
            $this->progressId = $progressId;
        }

        if( !isset( $data['percentage'] ) ){
            $data['percentage'] = 0;
        }
    
        if( !isset( $data['status'] ) ){
            $data['status'] = 'start';
        }
    
        if( !isset( $data['start'] ) ){
            $data['start'] = time();
        }
        
        if( isset( $data['status'] ) && $data['status'] == 'done' ){
            $data['end'] = time();
        }
        
        file_put_contents( $this->saveDirectory . $this->progressId , serialize( $data ) );
        
        $this->gc_progress();
        
        return $this->progressId;
        
    }
    
    /** @noinspection PhpUnused */
    public function startProgress(){
        return $this->saveProgress( [] );
    }
    
    public function gc_progress(){
        if( is_dir( $this->saveDirectory ) ){
            if( $dh = opendir( $this->saveDirectory ) ){
                while( ( $file = readdir( $dh ) ) !== false ){
                    if( $file == '.' || $file == '..' ){
                        continue;
                    }
                    $stat = stat( $this->saveDirectory . $file );
                    if( $stat[9] + $this->timeout < time() ){
                        unlink( $this->saveDirectory . $file );
                    }
                }
                closedir( $dh );
            }
        }
    }
    
    public function test(){
        return true;
    }
    
}