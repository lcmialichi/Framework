<?php

namespace Source\Model;

/*
|--------------------------------------------------------------------------
| ACESSO DO USUARIO INTERNO
|--------------------------------------------------------------------------
*/

use \Source\Model\ORM\Model as Schema;
use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\UserObject;
use Source\Exception\UserException;

/**
 * Model usuario_acesso
 */
#[Entity("usuario_acesso")]
class InternalUserAccess extends Schema
{
    #[Column(alias: "idAcesso", key: Column::PK)]
    private $acesso_id;
    #[Column(alias: "login")]
    private $acesso_login;
    #[Column(alias: "senha" )]
    private $acesso_senha;
    #[Column(alias: "status")]
    private $acesso_status;
    #[Column(alias: "ultimoAcesso")]
    private $acesso_ultimo_acesso;
    #[Column(alias: "idUsuario" )]
    private $acesso_id_usuario;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $acesso_data_insert;
    #[Column(alias: "ultimaAtualizacao", generatedValue: true )]
    private $acesso_data_update;

    public function findByAccess(string $access)
    {
        return Schema::select()->where("acesso_login", $access)->one();
    }

    /**
     * Cadastra acesso no BD 
     *  - Nao permite dois logins iguais.
     */
    public function register(UserObject $userObject)
    {
       
        $this->acesso_login = $userObject->getLogin();
        $this->acesso_senha = $userObject->getSenha();
        $this->acesso_status = $userObject->getStatus();
        $this->acesso_id_usuario = $userObject->getUserId();
        return $this->save();

    }

    /**
     * Atualiza dados de acesso do usuario
     */
    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("acesso_id" , $id)->execute();
    }

 

}