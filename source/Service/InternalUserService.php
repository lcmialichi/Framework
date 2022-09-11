<?php

namespace Source\Service;

use Source\Router\Request;
use Source\Attributes\Response;
use Source\Attributes\Permission;
use Source\Model\InternalUser;
use Source\Model\InternalUserAccess;
use Source\Service\ValueObject\UserObject;
use Source\Exception\UserException;
use Source\Http\Response\API;

#[Response(API::class)]
class InternalUserService 
{
    public function  __construct( private $router ) {}

    #[Permission(
        // service: Permission::SMSCAMPANHA, 
        // level: Permission::
        )]
    public function register( Request $request ) : array
    {

        $inputs = $request->inputs();
        $dadosUsuario = $inputs["dadosUsuario"];
        $acesso = $inputs["acesso"];

        $userObject =  new UserObject(
            idEmpresa: $dadosUsuario["idEmpresa"],
            idContrato: $dadosUsuario["idContrato"],
            idCsg: $dadosUsuario["idCsg"],
            idSuperior: $dadosUsuario["idSuperior"],
            idWebcred: $dadosUsuario["idWebcred"],
            idPerfil: $dadosUsuario["idPerfil"],
            nome: $dadosUsuario["nome"],
            email: $dadosUsuario["email"],
            cpf: $dadosUsuario["cpf"],
            idGrupo: $dadosUsuario["idGrupo"],
            dataNascimento: $dadosUsuario["dataNascimento"],
            login: $acesso["login"],
            senha: $acesso["senha"],
            status: $acesso["status"],
            isRobo: $dadosUsuario["isRobo"]
        );

        $internalUser = new InternalUser;
        $internalUserAccess = new InternalUserAccess;

        if( $internalUser->findByCpf($userObject->getCpf()) ){
            throw new UserException("Usuario ja possui cadastro");

        }else if ($internalUserAccess->findByAccess($userObject->getLogin())){
            throw new UserException("Acesso ja cadastrado");

        }

            $userId = $internalUser->register($userObject);
            $userObject->setUserId($userId);
            $userId = $userObject->getUserId();
           
        $accessId = $internalUserAccess->register($userObject);

        return [
            "message" => "Usuario Cadastrado com sucesso",
            "data" => [
                "idusuario" => $userId,
                "idacesso" => $accessId
            ]
        ];

    }

     /**
     * Atualiza dados do usuario
     */
    // #[Permission(Permission::USER_CREATE_ALL)]
    public function update( Request $params ) : array
    {

        return [];

    }

    /**
     * Consulta todos os usuarios da empresa
     */
    // #[Permission(Permission::USER_UPDATE_ALL)]
    public function consultAll( Request $params ) : array
    {
        $user = new InternalUser();

        $users = $user->all(useAlias: true);
        if(!$users){
            throw new \Exception("Nenhum usuario encontrado!", 200);
        }

        return [
            "message" => "Sucesso",
            "data" => [
                "usuarios" => $users
            ]
        ];

    }

     /**
     * Consulta usuario pelo ID
     */
    // #[Permission(Permission::USER_CONSULT)]
    public function consultById( Request $params ) : array
    {
        var_dump($params);exit;
        if(!isset($params['id'])){
            throw new \Exception("Id não informado!", 422);
        }

        $user = new InternalUser();
        $users = $user->find(id: $params['id'], useAlias: true);
        var_dump($user);

        if(!$users){
            throw new \Exception("Nenhum usuario encontrado!", 200);
        }

        return [
            "message" => "Sucesso",
            "data" => [
                "clientes" => $users
            ]
        ];
    }

    public function teste( Request $params ) 
    {
       var_dump($params->query());exit;
    }

 

}