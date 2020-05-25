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

namespace Sssm\Base\Models;
use CodeIgniter\Model;

class Dbtest extends Model{
    protected $table = 'users';
    protected $primaryKey = 'sid';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [ 'login_id' , 'password_hash' , 'last_logged_in_at' ];

    protected  $useTimestamps = true ;
    protected  $createdField = 'created_at';
    protected  $updatedField = 'updated_at';
    protected  $deletedField = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    function alldata(){
        return $this->findAll();
    }

}