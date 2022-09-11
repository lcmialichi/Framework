<?php

namespace Source\Model;

/*
|--------------------------------------------------------------------------
| USUARIO INTERNO
|--------------------------------------------------------------------------
*/

use \Source\Model\ORM\Model as Schema;
use \Source\Service\ValueObject\UserObject;
use Source\Exception\UserException;
use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;


/**
 *  Model usuario
 */
#[Entity("usuario")]
class InternalUser extends Schema { 
 
    #[Column(alias: "idUsuario", key: Column::PK)]
    private $usuario_id;
    #[Column(alias: "idEmpresa")]
    private $usuario_id_empresa;
    #[Column(alias: "idContrato")]
    private $usuario_id_contrato;
    #[Column(alias: "idCsg")]
    private $usuario_id_csg;
    #[Column(alias: "isRobo")]
    private $usuario_is_robo;
    #[Column(alias: "idSuperior")]
    private $usuario_id_superior;
    #[Column(alias: "idWebcred")]
    private $usuario_id_webcred;
    #[Column(alias: "idPerfil")]
    private $usuario_id_perfil;
    #[Column(alias: "nome")]
    private $usuario_nome;
    #[Column(alias: "email")]
    private $usuario_email;
    #[Column(alias: "cpf")]
    private $usuario_cpf;
    #[Column(alias: "idGrupo")]
    private $usuario_id_grupo;
    #[Column(alias: "dataNascimento")]
    private $usuario_nascimento;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $usuario_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $usuario_data_update;
    
    /**
     * Retorna dados do usuario e acesso pelo acesso_login
     * @return object|bool
     */
    public function findByAccess( string $access ) : object|bool
    {
        return Schema::select()
                        ->join("usuario_acesso  as t2", "usuario.usuario_id", "=" , "t2.acesso_id_usuario")
                        ->where("acesso_login", $access)
                        ->one();
                    
    }

    public function findByCpf(int $cpf, ?bool $useAlias = false )
    {
        return Schema::select( useAlias: $useAlias)->where("usuario_cpf", $cpf)->one();
    }

    /**
     * Cadastra usuario 
     */
    public function register(UserObject $userObject) : int
    {
     
        $this->usuario_id_empresa = $userObject->getIdEmpresa(isNullable: true);
        $this->usuario_is_robo = $userObject->getIsRobo(isNullable: true) ?? 0;
        $this->usuario_id_contrato = $userObject->getIdContrato(isNullable: true);
        $this->usuario_id_csg = $userObject->getIdCsg(isNullable: true);
        $this->usuario_id_superior = $userObject->getIdSuperior(isNullable: false);
        $this->usuario_id_webcred = $userObject->getIdWebcred(isNullable: true);
        $this->usuario_id_perfil = $userObject->getIdPerfil(isNullable: true);
        $this->usuario_nome = $userObject->getNome();
        $this->usuario_email = $userObject->getEmail();
        $this->usuario_cpf = $userObject->getCpf();
        $this->usuario_id_grupo = $userObject->getIdGrupo(isNullable: true);
        $this->usuario_nascimento = $userObject->getDataNascimento(isNullable: true);
        return $this->save();
    }

    
} 