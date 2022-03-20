<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
class Auth extends BaseController
{
    use ResponseTrait;
    public function __construct() {
        helper('secure_password');
        }
    public function index()
    {
        try {
         $username = $this->request->getPost('username');
         $password = $this->request->getPost('password');

         $usuarioModel = new UsuarioModel();

         $validateUsuario = $usuarioModel->where('username', $username)->first();

         if($validateUsuario == null)
           return $this->failNotFound('usuario no encontrado');

        if(verifyPassword($password, $validateUsuario["password"])):
            $jwt = $this->generateJWT($validateUsuario);
            return $this->respond(['Token' => $jwt], 201);

        else:
                return $this->failValidationError('contraseña invalida');
             endif;
 
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
          }
    }

    protected function generateJWT($usuario)
    {
        $key = Services::getSecretKey();
        $time = time();
        $payload =[
            'aud' =>base_url(),
            'iat' => $time, //como entero el tiempo
            'exp' => $time, //como entero el tiempo cuando expira el token
            'data'=> [
                'nombre' => $usuario['nombre'],
                'username' => $usuario['username'],
                'rol' => $usuario['rol_id']
            ]
        ];

    $jwt = JWT::encode($payload, $key);
    return $jwt;
   }
}
