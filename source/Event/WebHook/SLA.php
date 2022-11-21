<?php

namespace Source\Event\WebHook;

class SLA extends WebHook{

    /**
     * Disparo notificaçao da tabulaçao
     *
     * @param string $idAttendance
     * @param string $idProduct
     * @param string $idTab
     * @param string $idStage
     * @param int $origin
     * 
     * 
     */
    public function __construct(
        private int $idTab,
        private int $idAttendance, 
        private int $idProduct,
        private int $idStage,
        private string $hashClient,
        private int $idUser,
        private int $origin
        )
    {   
        parent::__construct("SLA");
    }
    
    /**
     *
     * @return array
     */
    public function provider() : array
    {
        return [
            "idTab" => $this->idTab,
            "idAttendance" => $this->idAttendance, 
            "idStage" => $this->idStage,
            "idProduct" => $this->idProduct,
            "hashClient" => $this->hashClient,
            "idUsuario" => $this->idUser 
        ];
    }
 
}
