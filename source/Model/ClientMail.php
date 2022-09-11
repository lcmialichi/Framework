<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientMailObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_email")]
class ClientMail extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $email_id;
    #[Column(alias: "idCliente")]
    private $email_id_cliente;
    #[Column(alias: "email")]
    private $email_email;
    #[Column(alias: "idOrigem")]
    private $email_id_origem;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $email_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $email_data_update;

    /**
     * Retorna objeto do e-mail do cliente para comparação
     * @param string $hash 
     * @return ClientMailObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientMailObject|bool
    {
        $result = Schema::select(useAlias: true)->where("email_id_cliente", $id)->last("email_id");

        if($result){
            return new ClientMailObject(
                idCliente: $result->idClient,
                email: $result->email
            );
        }
        return false;
    }

    public function register(ClientMailObject $clientMailObject)
    {
        $this->email_id_cliente = $clientMailObject->getIdClient();
        $this->email_email = $clientMailObject->getMail();
        $this->email_id_origem = Origin::getOrigin();

        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("email_id_cliente" , $id)->execute();
    }

}