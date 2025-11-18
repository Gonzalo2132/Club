<?php

namespace App\Models;

use CodeIgniter\Model;

class PagosModel extends Model
{
    protected $table      = 'pagos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_socio', 'id_liquidacion', 'id_caja', 'monto', 'fecha_pago', 
                                'fecha_alta', 'fecha_borrado', 'fecha_edicion'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creado';
    protected $updatedField  = 'fecha_borrado';
    protected $deletedField  = 'fecha_edicion';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
