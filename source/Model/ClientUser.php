<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientUserObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_acesso")]
class ClientUser extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $acesso_id;
    #[Column(alias: "idCliente")]
    private $acesso_id_cliente;
    #[Column(alias: "usuario")]
    private $acesso_login;
    #[Column(alias: "senha")]
    private $acesso_senha;
    #[Column(alias: "idOrigem")]
    private $acesso_id_origem;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $acesso_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $acesso_data_update;

    /**
     * Retorna objeto dos acessos do cliente para comparação
     * @param string $hash 
     * @return ClientUserObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientUserObject|bool
    {
        $result = Schema::select(useAlias: true)->where("acesso_id_cliente", $id)->last("acesso_id");

        if($result){
            return new ClientUserObject(
                idCliente: $result->idClient,
                usuario: $result->usuario,
                senha: $result->senha
            );
        }
        return false;
    }

    public function register(ClientUserObject $clientUserObject)
    {
        $this->acesso_id_cliente = $clientUserObject->getIdClient();
        $this->acesso_login = $clientUserObject->getUser();
        $this->acesso_senha = $clientUserObject->getPassword();
        $this->acesso_id_origem = Origin::getOrigin();

        return $this->save();
    }

    public function updateModel(array $data, int $id)
    {
        return $this->update($data)->where("acesso_id_cliente" , $id)->execute();
    }

    public function verifyUser(string $user)
    {
        $result = Schema::select(useAlias: true)->where("acesso_login", $user)->one();

        if($result){
            return true;
        }
        return false;
    }
}