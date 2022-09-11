<?php

namespace Source\Log;

use Source\Enum\AttendanceLogAction;
use Source\Model\AttendanceLog as LogAttendance;
use Source\Service\ValueObject\AttendanceLogObject;
use Source\Domain\Token;

/**
 * constroi o log de atendimento
 */
class AttendanceLog{

    /**
     *  Salva acao efetuada pelo usuario em cima de um atendimento em LOG
     *
     * @param AttendanceLogAction $acao
     * @param mixed $idAtendimento
     * @return void
     */
    public static function save( AttendanceLogAction $acao, mixed $idAtendimento ) : void
    {
        (new LogAttendance)->register( new AttendanceLogObject(
            idAcao: $acao->value,
            idUsuario: Token::getUserId(),
            idAtendimento: $idAtendimento
        ));
    }

}