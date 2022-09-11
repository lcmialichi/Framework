<?php

namespace Source\Api\Bank\C6Bank;

use Source\Curl\Curl;
use Source\Curl\CurlResponse;
use Source\Exception\BankException;
use Source\Api\Bank\DTO\FormLinkTransferObject;
use Source\Api\Bank\Contracts\FormLinkInterface;

class FormLink  extends Curl implements FormLinkInterface
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
                url: "https://marketplace-proposal-service-api-p.c6bank.info/marketplace/proposal/formalization-url?proposalNumber=" . $proposal,
                header: $this->header
            );
    }

    /**
     *
     * @param integer $proposal
     * @return FormLinkTransferObject
     */
    public function consultDTO( int $proposal ) : FormLinkTransferObject
    {
        $bankRequest = $this->consult($proposal);
        $data = json_decode($bankRequest->getResponse());

        if($bankRequest->getInfo("http_code") != 200){
            throw new BankException("Proposta nao possui link de formalizaçao");

        }

        return new FormLinkTransferObject(
            httpCode: $bankRequest->getInfo("http_code"),
            formalizationUrl: $data->url,
            status: $data->status
        );
    }
}