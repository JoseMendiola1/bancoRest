<?php
namespace App\Models\CustomRules;

use App\Models\ClienteModel;

class MyCustomRules{

    public function is_valid_cliente(int $id): bool{
        $model = new ClienteModel();
        $cliente = $model->find($id);

        return $cliente == null ? false : true;
    }
    public function is_allow_cliente(int $id): bool
    {
        return $id > 4 ? false : true;
    }
}