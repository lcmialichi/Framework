<?php

namespace Source\Http\Response;

use Source\Log\Log;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Source\Event\WebHook\Command\BashPrint;

/**
 * Classe responsável por tratar as mensagens de resposta ao cliente
 */
class Script implements ResponseInterface
{

    public function handle(callable $script): bool
    {   
        try {
            $fromScript = $script();
            if(isset($fromScript["message"])){
                echo " [ OK ] message: {$fromScript["message"]}\n";

            }
            if(isset($fromScript["outPut"])){
                if(BashPrint::isInitialized()){
                    BashPrint::printOutput();
                    
                }
            }
            return true;
        } catch (BeforeValidException | ExpiredException | SignatureInvalidException $e) {
            return false;

        } catch (\PDOException $e) {
            echo "\033[41m [ ERROR ] \033[m " . "Message: Erro de Conexao\n";
            Log::critical($e->getMessage());
            exit;
            return false;
            
        } catch (\Exception $e ) {
            echo " [ FALSE ] " . "Message: {$e->getMessage()}\n";
            return false;

        } catch (\Error $e) { // Erros da aplicaçao 
            echo $e;exit;
            echo "\033[41m [ ERROR ] \033[m" . "Message: {$e->getMessage()}\n";
            Log::critical($e->getMessage());
            exit;
            return false;

        }
    }

  
}
