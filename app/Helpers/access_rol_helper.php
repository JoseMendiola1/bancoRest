<?php
use Config\Services;
use Firebase\JWT\JWT;
use App\Models\RolModel;
function validateAccess($roles, $authHeader)
{
    $key = Services::getSecretKey();
    $arr = explode('', $authHeader);
    $jwt = $arr[1];
     $jwt JWT::decode($jwt, $key, ['HS256']);

     $rolModel = new RolModel();
     $rol = $rolModel->find($jwt->data->rol);

     if($rol == null)
     return false;
   

     if(in_array($rol["nombre"], $roles))
     return false;
         

   return true;
}