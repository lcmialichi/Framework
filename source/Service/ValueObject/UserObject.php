<?php

namespace Source\Service\ValueObject;

use Source\Exception\UserException;

/*
|--------------------------------------------------------------------------
| USER OBJECT | Encapsulamento
|--------------------------------------------------------------------------
| Aqui sao feita as validacoes dos dados dos usuarios que chegam pela rota
| e que serao utilizados durante a execucao do servico, ou inseridos no banco
| de dados.
|
*/

class UserObject
{

    public function __construct(
        private ?int $idUsuario = null,
        private readonly mixed $idEmpresa = null,
        private readonly mixed $idContrato = null,
        private readonly mixed $idCsg = null,
        private readonly mixed $idSuperior = null,
        private readonly mixed $idWebcred = null,
        private readonly mixed $idPerfil = null,
        private readonly mixed $nome = null,
        private readonly mixed $email = null,
        private readonly mixed $cpf = null,
        private readonly mixed $idGrupo = null,
        private readonly mixed $dataNascimento = null,
        private readonly mixed $idAcesso = null,
        private readonly mixed $login = null,
        private readonly mixed $senha = null,
        private readonly mixed $status = null,
        private readonly mixed $isRobo = null
    ) 
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Dados do usuario | Model: InternalUser
    |--------------------------------------------------------------------------
    */

    public function getUserId() : int
    { 
        if($this->numericValidation($this->idUsuario)){
             return $this->idUsuario;
        }
        throw UserException::invalidField("idUsuario");
   
    }

    /**
     * Tomar muito cuidado aqui
     */
    public function setUserId(int $id)
    {
        $this->idUsuario = $id;
    }

    public function getIdEmpresa(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idEmpresa, $isNullable)){
            return $this->idEmpresa;
        }
        throw UserException::invalidField("idEmpresa");
    }

    public function getIdContrato(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idContrato, $isNullable)){
            return $this->idContrato;
        }
        throw UserException::invalidField("idContrato");
    }

    public function getIdCsg(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idCsg, $isNullable)){
            return $this->idCsg;
        }
        throw UserException::invalidField("idCsg");
    }

    public function getIdSuperior(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idSuperior, $isNullable)){
            return $this->idSuperior;
        }
        throw UserException::invalidField("idSuperior");
    }

    public function getIdWebcred(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idWebcred, $isNullable)){
            return $this->idWebcred;
        }
        throw UserException::invalidField("idWebcred");
    }

    public function getIdPerfil(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idPerfil, $isNullable)){
            return $this->idPerfil;
        }
        throw UserException::invalidField("idPerfil");
    }

    public function getNome(bool $isNullable = false): ?string
    {
        if (!is_numeric($this->nome) && count(explode(" ", $this->nome)) > 1) {
            return $this->nome;
        }else if (is_null($this->nome)  && $isNullable){
            return null;
        }
        throw UserException::invalidField("nome");

    }

    public function getEmail(bool $isNullable = false): ?string
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) && !is_null($this->email)) {
            return $this->email;
        }else if (is_null($this->email)  && $isNullable){
            return null;
        }
        throw UserException::invalidField("email");

    }

    public function getCpf(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->cpf, $isNullable)){
            return $this->cpf;
        }
        throw UserException::invalidField("cpf");

    }

    public function getIdGrupo(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idGrupo, $isNullable)){
            return $this->idGrupo;
        }
        throw UserException::invalidField("idGrupo");

    }

    
    public function getIsRobo(bool $isNullable = false): ?int
    {
        if($this->isRobo == 0 || $this->isRobo == 1){
            return $this->isRobo;

        }else if (is_null($this->isRobo) && $isNullable){
            return $this->isRobo;
        }

        throw UserException::invalidField("isRobo");

    }


    public function getDataNascimento(bool $isNullable = false): ?string
    {
        if (\DateTime::createFromFormat("Y-m-d", $this->dataNascimento) && !is_null($this->dataNascimento)) {
            return $this->dataNascimento;
        }else if (is_null($this->dataNascimento)  && $isNullable){
            return null;
        }
        throw UserException::invalidField("dataNascimento");

    }

    /**
     * Verifica se o campo de validaçao é numerico
     */
    private function numericValidation( mixed $item ,bool $isNullable = false ) : bool
    {
        if (is_numeric($item)) {
            return true;
        }else if(is_null($item) && $isNullable){
            return true;
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Dados de acesso | Model: InternalUserAccess
    |--------------------------------------------------------------------------
    */

    public function setIdAcesso($idAcesso){
        $this->idAcesso = $idAcesso;
    }

    public function getIdAcesso(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->idAcesso, $isNullable)){
            return $this->idAcesso;
        }
        throw UserException::invalidField("idAcesso");
    }

    public function getLogin(bool $isNullable = false): ?string
    {
        if (is_string($this->login) && !is_null($this->login)) {
                if(count(explode(".", $this->login)) == 2){
                    return $this->login;
                }
        }else if (is_null($this->login)  && $isNullable){
            return null;
        }
        throw UserException::invalidField("login");
    }

    public function getSenha(bool $isNullable = false): ?string
    {
        if (is_string($this->senha) && !is_null($this->senha)) {
            return $this->senha;
        }else if (is_null($this->senha)  && $isNullable){
            return null;
        }
        throw UserException::invalidField("senha");
    }   

    public function getStatus(bool $isNullable = false): ?int
    {
        if($this->numericValidation($this->status, $isNullable)){
            return $this->status;
        }
        throw UserException::invalidField("status");
    }

  

}
