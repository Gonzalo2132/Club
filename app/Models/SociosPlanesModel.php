<?php

namespace App\Models;

use CodeIgniter\Model;

class SociosPlanesModel extends Model
{
    protected $table      = 'socios_planes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField  = 'fecha_borrado';

    protected $allowedFields = ['id_socio', 'id_plan', 'fecha_borrado'];
}