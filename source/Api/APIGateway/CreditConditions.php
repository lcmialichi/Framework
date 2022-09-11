<?php

namespace Source\Api\APIGateway;

use Source\Model\Table;
use Source\Enum\Product;
use Source\Enum\SimulationType as Type;
use Source\Exception\APIGatewayException;
use Source\Exception\SimulationException;
use Source\Service\ValueObject\SimulationObject;

/*
|--------------------------------------------------------------------------
| CONDIÇOES DE CREDITO DO CLIENTE
|--------------------------------------------------------------------------
| Aqui é construido as condiçoes de credito para envio na api gateway, que
| podem ser diversas ex (cartao, inss, fgts, etc ...)
|
*/

final class CreditConditions{

    /**
     * @var ?Type
     */
    private ?Type $simulationType;
    /**
     * @var int
     */
    private readonly int $idBanco; #Mando APIGATEWAY
    /**
     * @var int
     */
    private readonly int $idConvenio; #Mando APIGATEWAY
    /**
     * @var int
     */
    private readonly int $idProduto;
    /**
     * @var int
     */
    private readonly string $codTabela;
    /**
     * @var int
     */
    private readonly int $idOrgao;
    /**
     * @var mixed
     */
    private readonly mixed $parcelas;
    /**
     * @var ?float
     */
    private readonly ?float $valor;
    /**
     * @var ?float
     */
    private readonly ?float $margem;
    /**
     * @var ?float
     */
    private readonly ?float $renda;
    /**
     * @var ?bool
     */
    private readonly ?bool $seguro;



    public function __construct(
        private readonly SimulationObject $simulationObject
    ){

        $this->simulationType = type::tryFrom($simulationObject->getIdTipo()); 
    
        $table = (new Table)->find( #encontra tabela enviada
            id: $simulationObject->getIdTabelaBanco(),
            useAlias: true
        );

        if(!$table){
            throw new APIGatewayException("Tabela nao cadastrada");

        }

        $this->idBanco = $table->idBanco;
        $this->idConvenio = $table->idConvenio;
        $this->idProduto = $table->idProduto;
        $this->idOrgao = $table->idOrgao;
        $this->codTabela = $table->codigo;
        $this->seguro = $simulationObject->getSeguro(isNullable: true);
        $this->parcelas = $simulationObject->getPrazo(isNullable: true);
        $this->valor = $simulationObject->getValor(isNullable: true);
        $this->margem = $simulationObject->getMargem(isNullable: true);                                              
        $this->renda = $simulationObject->getRenda(isNullable: true);

    }

    public function getIdProduct()
    {
        return $this->idProduto;
    }

    public function getIdBank()
    {
        return $this->idBanco;
    }

    public function getIdConvenio()
    {
        return $this->idConvenio;
    }

    public function getMargem()
    {
        if (!is_numeric($this->margem )) {
            throw SimulationException::invalidField("margem");
            
        }

        return $this->margem;
    }

    public function getRenda()
    {
        if (!is_numeric($this->renda )) {
            throw SimulationException::invalidField("renda");
            
        }

        return $this->renda;
    }

    public function getValor()
    {
        return $this->valor;
    }
    public function getSeguro() : bool
    {
        return $this->seguro;
    }

    public function getParcelas()
    {
        return $this->parcelas;
    }

    public function getIdOrgao()
    {
        return  $this->idOrgao;
    }

    public function getTableCode()
    {
        return $this->codTabela;
    }

    public function getSimulationType()
    {
        return $this->simulationType;
    }
   
}