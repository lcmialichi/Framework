<?php

namespace Source\Script;

use Source\Exception\ScriptError;

trait ScriptTrait {
    
        /**
     * @return string
     */
    private function getExecuted() : string
    {   
        $command = filter_input(INPUT_SERVER, "SCRIPT_NAME");
        if(!$command){
            throw new ScriptError("A execuçao deve ser feita pela linha de comando");
        }
        
        return $command;
        
    }

    /**
     * Bloqueia script de rodar caso possua mais instancias em execucao
     * que o parametro enviado
     * 
     * @param int $count - numero de instancias permitidas 
     */
    private function blockScriptFromRunnig(int $count = 1) : bool 
    {
        $command = $this->getExecuted();
        $instances = shell_exec("ps aux | grep '$command' | grep -v grep | wc -l");
            if ($instances > $count) {
                throw new ScriptError("Numero de instancias em execuçao atingiu limite: '$command'");
            }
        return true;
    }
}
