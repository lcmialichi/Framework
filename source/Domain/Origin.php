<?php

namespace Source\Domain;

use Source\Exception\OriginError;

/*
|--------------------------------------------------------------------------
| Origem da requisicao
|--------------------------------------------------------------------------
| Aqui ficam armazenados em monostate os dados da origem do cliente
| utiliza appKey para coletar origem
|
*/

class Origin {

    /**
     * @var int
     */
    private static $origin;
    /**
     * @var boolean
     */
    private static $initialized = false;

    /**
     * Só é possivel setar a origem uma vez.
     *
     * @param integer $idOrigin
     * @return void
     * @throws OriginError
     */
    public static function setOrigin(int $idOrigin) : void
    {
        if(!Origin::$initialized){
            Origin::$origin = $idOrigin;
            Origin::$initialized = true;
            return;
        }
       
        throw new OriginError("Origem ja definida");
    }

    public static function getOrigin() : int
    {
        if(Origin::$origin){
            return Origin::$origin;
        }

        return false;
        
    }
}