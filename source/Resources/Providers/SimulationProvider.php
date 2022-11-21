<?php

namespace Source\Resources\Providers;

use Source\Request\Request;
use Source\Container\Container;
use Source\Service\ValueObject\SimulationObject;

class SimulationProvider extends Provider {
    
        public function register()
        {
            $request = resolve(Request::class);
            
            if(empty($dadosSimulacao = $request->dadosSimulacao)){
                return;
            }
 
            $this->app->bind(SimulationObject::class, function() use ($dadosSimulacao){
                return new SimulationObject(
                    idTipo: $dadosSimulacao["idTipo"],
                    valor: $dadosSimulacao["valor"],
                    prazo: $dadosSimulacao["prazo"],
                    margem: $dadosSimulacao["margem"],
                    renda: $dadosSimulacao["renda"],
                    seguro: (int)$dadosSimulacao["seguro"],
                    idTabelaBanco: $dadosSimulacao["idTabelaBanco"]
                );
            });
        }
}