<?php

namespace Source\Service\ValueObject;

use Source\Exception\LogError;

class AttendanceLogObject extends Validation{

    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idAcao = null,
        private readonly mixed $idUsuario = null,
        private readonly mixed $idAtendimento = null,
    ){}

    public function getId()
    {
        return $this->id;
    }

    public function getIdAcao( bool $isNullable = false )
    {               
        if(parent::nullEnabled($this->idAcao, $isNullable)){
            return $this->idAcao;
        }
        if (!is_numeric($this->idAcao)) {
            throw LogError::invalidField("log_id_acao");
        }
        
        return $this->idAcao;
    }

    public function getIdUsuario( bool $isNullable = false )
    {               
        if(parent::nullEnabled($this->idUsuario, $isNullable)){
            return $this->idUsuario;
        }
        if (!is_numeric($this->idUsuario)) {
            throw LogError::invalidField("log_id_usuario");
        }
    
        return $this->idUsuario;
    }

    public function getIdAtendimento( bool $isNullable = false )
    {               
        if(parent::nullEnabled($this->idAtendimento, $isNullable)){
            return $this->idAtendimento;
        }
        if (!is_numeric($this->idAtendimento)) {
            throw LogError::invalidField("log_id_atendimento");
        }
    
        return $this->idAtendimento;
    }


}