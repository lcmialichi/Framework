<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use \Source\Service\ValueObject\CredentialObject;

#[Entity("banco_credencial")]
class BankCredencial extends Schema{

    #[Column(alias: "id", key: Column::PK)]
    private $credencial_id;
    #[Column(alias: "idUsuario")]
    private $credencial_id_usuario;
    #[Column(alias: "acesso")]
    private $credencial_acesso;
    #[Column(alias: "senha")]
    private $credencial_senha;

    /**
     * Coleta as credenciais do usuario para um banco especifico.
     *
     * @param integer $userId
     * @param integer $bankId
     * @return CredentialObject
     */
    public function getCrencials( int $userId, int $bankId ) : CredentialObject
    {
        $data = Schema::select()->join(
            "usuario_credencial_banco as t2", "t2.banco_id_credencial", "=", "banco_credencial.credencial_id"
            )
            ->join("usuario as t3", "t3.usuario_id", "=", "credencial_id_usuario")
            ->where("banco_id_usuario", $userId)
            ->where("credencial_id_banco", $bankId)
            ->one();

        if(!$data){
            return false;
        }
        return new CredentialObject(
            id: $data->credencial_id,
            userOriginId: $data->credencial_id_usuario,
            access: $data->credencial_acesso,
            userOriginCpf: $data->usuario_cpf,
            password: $data->credencial_senha,
            bank: $data->credencial_id_banco
        );

    }

}