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
     * 
     */
    public function __construct(
        private int $idTab,
        private int $idAttendance, 
        private int $idProduct,
        private int $idStage 
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
            "idProduct" => $this->idProduct
        ];
    }
 
}
