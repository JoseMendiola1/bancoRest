<?php
namespace App\Filter\FilterInterface;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
class AuthFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        //se ejecuta antes que el controlador
        try {
            $key = Services::getSecretKey();
            $authHeader = $request->getServer('HTTP__AUTHORIZATION');

            if($authHeader == null)
             return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'No se a enviado el JWT requerido');
             $arr = explode('', $authHeader);
             $jwt = $arr[1];

             $jwt JWT::decode($jwt, $key, ['HS256']);
             
             $rolModel = new RolModel();
             $rol = $rolModel->find($jwt->data->rol);

             if($rol == null)
             return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'El rol es invalido');
             return true;
        } catch(ExpiredException $ee){
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'Su token a expirado');
        } 
        catch (\Exception $e) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Sucedio un error en el servidor al validar el token ');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //se ejecuta despues que el controlador
    }
}