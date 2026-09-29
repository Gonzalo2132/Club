<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanesModel extends Model
{
    protected $table      = 'planes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    // Campos permitidos para guardar/actualizar
    protected $allowedFields = ['nombre', 'descripcion', 'activo', 'fecha_alta', 'fecha_edicion', 'fecha_borrado'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edicion';
    protected $deletedField  = 'fecha_borrado';
}