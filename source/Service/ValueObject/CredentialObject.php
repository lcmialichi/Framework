<?php

namespace Source\Service\ValueObject;

/**
 * @todo validar campos
 */
class CredentialObject{

    public function __construct(
        private mixed $id = null,
        private mixed $userOriginId = null,
        private mixed $userOriginCpf = null,
        private mixed $access = null,
        private mixed $password = null,
        private mixed $bank = null
    )
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserOriginId()
    {
        return $this->userOriginId;
    }

    public function getAccess()
    {
        return $this->access;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function getUserOriginCpf()
    {
        return $this->userOriginCpf;
    }

}