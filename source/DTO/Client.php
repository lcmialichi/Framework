<?php

namespace Source\DTO;

use Source\Validators\Validator;
use Source\Service\ValueObject\ClientObject;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\ClientDocumentObject;

class Client extends DataTransferObject
{

    /**
     * @var ClientObject|bool
     */
    private $personal;
    /**
     * @var ClientAddressObject|bool
     */
    private $address;
    /**
     * @var ClientBankObject|bool
     */
    private $bank;
    /**
     * @var ClientBankObject|bool
     */
    private $bankEndorsement;
    /**
     * @var ClientContactObject|bool
     */
    private $contact;
        /**
     * @var ClientDocumentObject|bool
     */
    private $document;
    /**
     * @var ClientEmployerObject|bool
     */
    private $employer;

}
