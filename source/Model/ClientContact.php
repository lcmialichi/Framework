<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientContactObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_contato")]
class ClientContact extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $contato_id;
    #[Column(alias: "idCliente")]
    private $contato_id_cliente;
    #[Column(alias: "ddd")]
    private $contato_ddd;
    #[Column(alias: "telefone")]
    private $contato_telefone;
    #[Column(alias: "idOrigem")]
    private $contato_id_origem;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $contato_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $contato_data_update;

    /**
     * Retorna objeto do contato do cliente para comparação
     * @param string $hash 
     * @return ClientContactObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientContactObject|bool
    {
        $result = Schema::select(useAlias: true)->where("contato_id_cliente", $id)->last("contato_id");

        if($result){
            return new ClientContactObject(
                idCliente: $result->idClient,
                ddd: $result->ddd,
                telefone: $result->telefone,
            );
        }
        return false;
    }

    public function findByClientId( int $id ){
        return Schema::select(useAlias: true)->where("contato_id_cliente", $id)->get();
    }

    public function register(ClientContactObject $clientContactObject)
    {
        $this->contato_id_cliente = $clientContactObject->getIdClient();
        $this->contato_ddd = $clientContactObject->getDdd();
        $this->contato_telefone = $clientContactObject->getTelefone();
        $this->contato_id_origem = Origin::getOrigin();
        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("contato_id_cliente" , $id)->execute();
    }

}