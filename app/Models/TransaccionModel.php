<?php namespace App\Models;

use CodeIgniter\Model;

class TransaccionModel extends Model
{
    protected $table            = 'transaccion'; 
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['cuenta_id','tipo_transaccion_id','monto'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at'; 

    protected $validationRules  = [
        'moneda'                => 'required|numeric',
        'cuenta_id'             => 'required|integer|is_valid_cuenta',
        'tipo_transaccion_id'   => 'required|integer|is_valid_tipo_transaccion',       
    ];

    protected $validationMessages = [
        'cuenta_id'  => [
            'is_valid_cuenta' => 'estimado usuario debe de ingresar una cuenta valida'        
        ],
        
        'tipo_transaccion_id' => [
            'is_valid_tipo_transaccion' => 'estimado usuario debe de ingresar un tipo de transaccion valido'
        ]
     ];

    protected $skypValidation = false;

    public function TransaccionPorCliente($clienteId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('cuenta_id AS NumeroCuenta, cliente.nombre, cliente.apellido');
        $builder->select('tipo_transaccion.descripcion, transaccion.monto, transaccion.created_at AS FechaTransaccion');
        $builder->join('cuenta', 'transaccion.cuenta_id = cuenta.id');
        $builder->join('tipo_transaccion', 'transaccion.tipo_transaccion_id = tipo_transaccion.id');
        $builder->join('cliente', 'cuenta.cliente_id = cliente.id');
        $builder->where('cliente.id', $clienteId);
        $query  = $builder->get();
        return $query->getResult();

    }
}