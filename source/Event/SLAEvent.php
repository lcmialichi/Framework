<?php

namespace Source\Event;

use Source\Model\Attendance;

class SLAEvent extends ScriptEvent
{
    public $idProduto;
    public $idEtapa;
    public $idTabulacao;
    public $idAtendimento;
    public $hashClient;
    public $idUser;
    public $origin;

    /**
     *  {@inheritDoc}
     */
    public function handle(): array
    {
        $attendanceModel = new Attendance;
        $attendances = $attendanceModel->findAllByConfig();

        foreach ($attendances as $attendance) {
            $this->idProduto = $attendance->idProduto;
            $this->idEtapa = $attendance->idEtapa;
            $this->idTabulacao = $attendance->idTabulacao;
            $this->idAtendimento = $attendance->idAtendimento;
            $this->hashClient = $attendance->hashClient;
            $this->idUser = $attendance->idUsuario;
            $this->origin = $attendance->origem;

            if (!is_null($attendance->tempoLimite)) {
                $expireDate = date(
                    format: "Y-m-d H:i:s",
                    timestamp: strtotime($attendance->dataTabulacao) + $attendance->tempoLimite
                );

                if ((new \DateTime)->format("Y-m-d H:i:s") >= $expireDate) {
                    $this->notify();

                    $attendanceModel->updateModel(
                        data: ["atendimento_expirou_tab" => 1],
                        idAtendimento: $this->idAtendimento
                    );
                }
            }
        }

        return [
            "message" => "FINALIZADO",
            "outPut" => true
        ];
    }
}
