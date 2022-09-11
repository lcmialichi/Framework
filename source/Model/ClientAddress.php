<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientAddressObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_endereco")]
class ClientAddress extends Schema {

    #[Column(alias: "id", key: Column::PK)]
    private $endereco_id;
    #[Column(alias: "idCliente")]
    private $endereco_id_cliente;
    #[Column(alias: "uf")]
    private $endereco_uf;
    #[Column(alias: "cidade")]
    private $endereco_cidade;
    #[Column(alias: "bairro")]
    private $endereco_bairro;
    #[Column(alias: "rua")]
    private $endereco_rua;
    #[Column(alias: "numero")]
    private $endereco_numero;
    #[Column(alias: "idOrigem")]
    private $endereco_id_origem;
    #[Column(alias: "cep")]
    private $endereco_cep;
    #[Column(alias: "complemento")]
    private $endereco_complemento;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $endereco_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $endereco_data_update;

    /**
     * Retorna objeto do endereço do cliente para comparação
     * @param string $hash 
     * @return ClientAddressObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientAddressObject|bool
    {
        $result = Schema::select(useAlias: true)->where("endereco_id_cliente", $id)->last("endereco_id");

        if($result){
            return new ClientAddressObject(
                id: $result->id,
                idCliente: $result->idCliente,
                uf: strtoupper($result->uf),
                cidade: strtoupper($result->cidade),
                bairro: strtoupper($result->bairro),
                rua: strtoupper($result->rua),
                numero: $result->numero,
                cep: $result->cep,
                complemento: strtoupper($result->complemento)
            );
        }
        return false;
    }

    public function findByClientId( int $id ){
        return Schema::select(useAlias: true)->where("endereco_id_cliente", $id)->get();
    }

    public function register(ClientAddressObject $clientAddressObject)
    {
        
        $this->endereco_id_cliente = $clientAddressObject->getIdClient();
        $this->endereco_uf = $clientAddressObject->getUf();
        $this->endereco_cidade = $clientAddressObject->getCidade();
        $this->endereco_bairro = $clientAddressObject->getBairro();
        $this->endereco_rua = $clientAddressObject->getRua();
        $this->endereco_numero = $clientAddressObject->getNumero();
        $this->endereco_cep = $clientAddressObject->getCep();
        $this->endereco_id_origem = Origin::getOrigin();
        $this->endereco_complemento = $clientAddressObject->getComplemento(isNullable: true);

        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("endereco_id_cliente" , $id)->execute();
    }
}