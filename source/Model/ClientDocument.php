<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientDocumentObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_documento")]
class ClientDocument extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $documento_id;
    #[Column(alias: "idCliente")]
    private $documento_id_cliente;
    #[Column(alias: "documento")]
    private $documento_documento;
    #[Column(alias: "idTipo")]
    private $documento_id_tipo;
    #[Column(alias: "idOrigem")]
    private $documento_id_origem;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $documento_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $documento_data_update;

    /**
     * Retorna objeto dos documentos do cliente para comparação
     * @param string $hash 
     * @return ClientDocumentObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientDocumentObject|bool
    {
        $result = Schema::select(useAlias: true)->where("documento_id_cliente", $id)->last("documento_id");

        if($result){
            return new ClientDocumentObject(
                idCliente: $result->idClient,
                documento: $result->documento,
                idTipo: $result->idTipo
            );
        }
        return false;
    }

    public function register(ClientDocumentObject $clientDocumentObject)
    {
        $this->documento_id_cliente = $clientDocumentObject->getIdClient();
        $this->documento_documento = $clientDocumentObject->getDocument();
        $this->documento_id_tipo = $clientDocumentObject->getidTipo();
        $this->documento_id_origem = Origin::getOrigin();

        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("documento_id_cliente" , $id)->execute();
    }

}