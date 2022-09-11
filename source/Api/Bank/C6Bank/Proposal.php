<?php

namespace Source\Api\Bank\C6Bank;

use Source\Curl\Curl;
use Source\Curl\CurlResponse;
use Source\Exception\BankError;
use Source\Exception\BankException;
use \Source\Api\Bank\DTO\ProposalTransferObject;
use \Source\Api\Bank\Contracts\ProposalInterface;
use \Source\Api\Bank\Processor;

class Proposal extends Curl 
    implements ProposalInterface
{
    /**
     *
     * @var array
     */
    public array $header;

    public function __construct()
    {
        $this->header = [
            "Authorization: " . (new Access)->getToken()
        ];
    }

    /**
     *  @param integer $proposal
     *  @return CurlResponse
     */
    public function consult(int $proposal) : CurlResponse
    {
        return 
            $this->get(
                url: "https://marketplace-proposal-service-api-p.c6bank.info/marketplace/proposal?proposalNumber=" . $proposal,
                header: $this->header
            );
    }


    /**
     *  @param integer $proposal
     *  @return ProposalTransferObject
     */
    public function consultDTO( int $proposal ) : ProposalTransferObject
    {
        $api = $this->consult($proposal);
        if($api->getInfo("http_code") != 200){
            throw new BankException("Erro ao consultar proposta");
        }

        $proposal = json_decode($api->getResponse());

        if(!$proposal){
            throw new BankError("Json retornado Invalido!");
        }
        return  new ProposalTransferObject(
            httpCode: $api->getinfo("http_code"),
            status: $proposal->loan_track->situation,
            activity: $proposal->loan_track->current_activity_description,
            proposalNumber: $proposal->proposal_number,
            registrationDate: $proposal->registration_date,
            table: $proposal->financing_table_code
        );
    }

    /**
     * Retorna os dados da propsotas formatados no padrao do ATLAS
     *
     * @param integer $proposal
     * @return ProposalTransferObject
     */
    public function toPattern( int $proposal ) : ProposalTransferObject
    {
        return (new Processor( new Pattern ))->proposalProcessor(
           $this->consultDTO($proposal)
        );

    }                              
}