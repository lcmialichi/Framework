<?php

namespace Source\Api\Bank;

use Source\Enum\Banks;
use Source\Exception\BankError;
use Source\Api\Bank\Contracts\FormLinkInterface;
use Source\Api\Bank\Contracts\ProposalInterface;

class Factory {

    /**
     * Retorna a instancia de um banco que implemente ProposalInterface
     *
     * @param Banks $bank
     * @return ProposalInterface
     */
    public static function proposal( Banks $bank ) : ProposalInterface
    {
       $bankInstance = match ($bank) {
           Banks::C6BANK => new C6Bank\Proposal,
            default =>
                throw new BankError("[Factory] '" . $bank->name . "' [Proposal] Não possui classe para ser instanciado" )
           
        };

        if(!$bankInstance instanceof ProposalInterface) {
            throw new BankError("[Factory] A classe ". $bankInstance::class . " não implementa ProposalInterface");
        }

        return new $bankInstance;
    }

    /**
     * Retorna a instancia de um banco que implemente ProposalInterface
     *
     * @param Banks $bank
     * @return FormLinkInterface
     */
    public static function formLink( Banks $bank ) : FormLinkInterface
    {
       $bankInstance = match ($bank) {
           Banks::C6BANK => new C6Bank\FormLink,
            default =>
                throw new BankError("[Factory] '" . $bank->name . "' [FormLink] Não possui classe para ser instanciado" )
           
        };

        if(!$bankInstance instanceof FormLinkInterface) {
            throw new BankError("[Factory] A classe ". $bankInstance::class . " não implementa ProposalInterface");
        }

        return new $bankInstance;
    }

}