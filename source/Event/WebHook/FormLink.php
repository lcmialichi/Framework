<?php

namespace Source\Event\WebHook;

use Source\Api\Bank\DTO\FormLinkTransferObject;

class FormLink extends WebHook{

    /**
     *
     * @param FormLinkTransferObject $formLinkTransferObject
     * @param string $clientHash
     */
    public function __construct(
        private FormLinkTransferObject $formLinkTransferObject,
        private string $clientHash
    ){
      parent::__construct("LINK FORMALIZACAO");
    }

    public function provider() : array
    {   
      return [
        "clientHash" => $this->clientHash,
        "formalizationUrl" => $this->formLinkTransferObject->formalizationUrl
      ];

    }
}