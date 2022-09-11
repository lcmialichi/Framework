<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\AttendanceLogObject;

#[Entity("atendimento_log")]
class AttendanceLog extends Schema{
 
    #[Column(alias:"id", key: Column::PK)]
    private $log_id;
    
    #[Column(alias:"acao")]
    private $log_id_acao;
    
    #[Column(alias:"idUsuario")]
    private $log_id_usuario;

    #[Column(alias:"idUsuario")]
    private $log_id_atendimento;
    
    #[Column(alias:"ultimaAtualizacao", generatedValue: true)]
    private $log_data_update;
    
    #[Column(alias:"dataCadastro", generatedValue: true)]
    private $log_data_insert;

    /**
     *
     * @param AttendanceLogObject $attendanceLogObject
     * @return void
     */
    public function register( AttendanceLogObject $attendanceLogObject )
    {
        $this->log_acao = $attendanceLogObject->getIdAcao();
        $this->log_id_usuario = $attendanceLogObject->getIdUsuario();
        $this->log_id_acao = $attendanceLogObject->getIdAcao();
        $this->log_id_atendimento = $attendanceLogObject->getIdAtendimento();
        return $this->save();
         
    }
}