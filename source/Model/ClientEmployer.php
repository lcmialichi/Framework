<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientEmployerObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_empregador")]
class ClientEmployer extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $empregador_id;
    #[Column(alias: "idCliente")]
    private $empregador_id_cliente;
    #[Column(alias: "especie")]
    private $empregador_especie;
    #[Column(alias: "uf")]
    private $empregador_uf;
    #[Column(alias: "matricula")]
    private $empregador_matricula;
    #[Column(alias: "idOrigem")]
    private $empregador_id_origem;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $empregador_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $empregador_data_update;

    /**
     * Retorna objeto do empregador do cliente para comparação
     * @param string $hash 
     * @return ClientEmployerObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientEmployerObject|bool
    {
        $result = Schema::select(useAlias: true)->where("empregador_id_cliente", $id)->last("empregador_id");

        if($result){
            return new ClientEmployerObject(
                idCliente: $result->idClient,
                especie: $result->especie,
                uf: strtoupper($result->uf),
                matricula: $result->matricula
            );
        }
        return false;
    }

    public function register(ClientEmployerObject $clientEmployerObject)
    {
        $this->empregador_id_cliente = $clientEmployerObject->getIdClient();
        $this->empregador_especie = $clientEmployerObject->getSpecie();
        $this->empregador_uf = $clientEmployerObject->getUf();
        $this->empregador_matricula = $clientEmployerObject->getSubscription();
        $this->empregador_id_origem = Origin::getOrigin();
        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("empregador_id_cliente" , $id)->execute();
    }

}