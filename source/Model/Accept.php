<?php

namespace Source\Model;

use Source\Domain\Origin;
use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\AcceptObject;

#[Entity("cliente_aceite")]
class Accept extends Schema
{
    #[Column(alias: "id", key: Column::PK)]
    private $aceite_id;
    
    #[Column(alias: "finalidade")]
    private $aceite_finalidade;
    
    #[Column(alias: "idOrigem")]
    private $aceite_id_origem;
    
    #[Column(alias: "idAcao")]
    private $aceite_id_acao;
    
    #[Column(alias: "idCliente")]
    private $aceite_id_cliente;
    
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $aceite_data_cadastro;
    
    #[Column(alias: "ultimaAtualizacao",  generatedValue: true)]
    private $aceite_data_update;

    /**
     * Registra aceite de cliente
     *
     * @param AcceptObject $acceptObject
     * @return integer|boolean
     */
    public function register( AcceptObject $acceptObject ) : int|bool
    {   
        $this->aceite_finalidade = $acceptObject->getFinalidade();
        $this->aceite_id_origem = Origin::getOrigin();
        $this->aceite_id_acao = $acceptObject->getIdAcao();
        $this->aceite_id_cliente = $acceptObject->getIdCliente();
        return $this->save();

    }

    /**
     * Retorna lista de aceites do cliente
     *
     * @param integer $idClient
     * @return array|boolean
     */
    public function findByClientId(int $idClient): array|bool
    {
        return Schema::select(useAlias:true)->where("aceite_id_cliente", $idClient)->execute();
    }
}
