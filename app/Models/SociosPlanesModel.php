<?php

namespace App\Models;

use CodeIgniter\Model;

class SociosPlanesModel extends Model
{
    protected $table      = 'socios_planes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    
    protected $allowedFields = ['id_socio', 'id_plan'];
}