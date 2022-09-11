<?php

namespace Source\Service;

use Source\Router\Request;
use Source\Http\Response\API;
use Source\Model\InternalUser;
use Source\Model\Permission;
use Source\Attributes\Response;
use Firebase\JWT\JWT;
use Source\Exception\UserException;
use Source\Model\InternalUserAccess;

#[Response(API::class)]
class AuthenticateService
{
    const IS_ROBOT = 1;

    public function authenticate( Request $request ) : array
    {      
        $inputs = $request->inputs();
        if(!$inputs["login"] || !$inputs["senha"]){
            throw new UserException("Login ou senha não informados");
        }

        $credencials = (new InternalUser)->findByAccess( $inputs["login"] );

        if(empty($credencials)){
            throw UserException::invalidUser();

        }else if($credencials->acesso_senha !== $inputs["senha"]){
            throw UserException::invalidUser();

        }   

        $accessId = $credencials->acesso_id;
        $userId = $credencials->usuario_id;
        $isRobot = $credencials->usuario_is_robo;

        $expiresAt = self::IS_ROBOT == $isRobot ? strtotime("+12 hour") : strtotime("+1 hour");

        $userPermissions = Permission::getSimplifyed($userId)->getPermissions();  
        foreach( $userPermissions as $key => $value ){
            $permissions[$key]  = [$value['level'], $value['type']];
        }

        $accessDate = date("Y-m-d H:i:s");
        $access = [
            "userId"  =>  $userId,
            "userAccessId" => $accessId,
            "permissions"  => $permissions ?? [],
            "expireDate" =>  date("Y-m-d H:i:s", $expiresAt), 
            "accessDate" => $accessDate

        ];

        $accessToken = JWT::encode($access, getenv('JWTKEY'), 'HS256');
         (new InternalUserAccess)->updateModel( 
            [
                "acesso_ultimo_acesso" => $accessDate
            ], 
            id: $accessId
         );

        return [
            "message" => "Usuario autenticado com sucesso!",
            "data" =>[
                "nome" => $credencials->usuario_nome,
                "token" => [
                    "accessToken" => $accessToken,
                    "expireIn" =>  $expiresAt - time()
                ]
            ]
        ];
  
    }

}