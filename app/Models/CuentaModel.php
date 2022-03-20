<?php namespace App\Models;

use CodeIgniter\Model;

class CuentaModel extends Model
{
    protected $table            = 'cuenta'; 
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['moneda','fondo','cliente_id'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at'; 

    protected $validationRules  = [
        'moneda'       => 'required|alpha_space|min_length[3]|max_length[3]',
        'fondo'        => 'required|numeric',
        'cliente_id'   => 'required|integer|is_valid_cliente|is_allow_cliente',       
    ];

    protected $validationMessages = [
        'cliente_id'  => [
            'is_valid_cliente' => 'Debe de ingresar un cliente alido',
            'is_allow_cliente' => 'Debe de ingresar un cliente en la lista permitida'
        ]
    ];

    protected $skypValidation = false;
}