<?php

namespace Source\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Source\Api\Telegram\Messanger;
use Monolog\Formatter\LineFormatter;
use Source\Domain\Token as Token;
use function \apache_request_headers;

class Log{

    public static function debug($message, ?array $params = null)
    {
        $logName = getenv("PROJECT");
        $log = new Logger( $logName );

        $stream = new StreamHandler('log/debug.log', Logger::DEBUG);
        $stream->setFormatter( self::formatter() );
        $log->pushHandler($stream);

        $log->pushProcessor(function ($record) {
            $record['extra']['client'] =
            [
                "REMOTE_ADDR" =>  $_SERVER['REMOTE_ADDR']
            ];

        return $record;
        });

        $log->debug($message, $params ?? []);

    }

    public static function notice($message,  ?array $params = null)
    {
        $logName = getenv("PROJECT");
        $log = new Logger( $logName );

        $stream = new StreamHandler('log/notice.log', Logger::NOTICE);
        $stream->setFormatter( LOG::formatter() );
        $log->pushHandler($stream);

        $log->pushProcessor(function ($record) {
            $record['extra']['client'] =
            [
                "REMOTE_ADDR" =>  $_SERVER['REMOTE_ADDR']
            ];

        return $record;
        });

        $log->notice($message, $params ?? []);
    }

    public static function warning($message,  ?array $params = null)
    {
        $logName = getenv("PROJECT");
        $log = new Logger( $logName );

        $stream = new StreamHandler('log/warning.log', Logger::WARNING);
        $stream->setFormatter( LOG::formatter() );
        $log->pushHandler($stream);

        $log->pushProcessor(function ($record) {
            $record['extra']['client'] =
            [
                "REMOTE_ADDR" =>  $_SERVER['REMOTE_ADDR']
            ];

        return $record;
        });

        $log->warning($message, $params ?? []);

    }

    /**
     * Eventos de interesse da plataforma ex: simulaçoes efetuadas
     */
    public static function info($message,  ?array $params = null)
    {
        $logName = getenv("PROJECT");
        $log = new Logger( $logName );

        $stream = new StreamHandler('log/info.log', Logger::INFO);
        $stream->setFormatter( LOG::formatter() );
        $log->pushHandler($stream);

        $log->pushProcessor(function ($record) {
            $record['extra']['client'] =
            [
                "REMOTE_ADDR" =>  $_SERVER['REMOTE_ADDR']
            ];

            if(Token::isInitialized()){
                $record["extra"]["user"]["USER_ID"] = Token::getUserId();
             }

             $record['extra']['request'] =
            [
                "Route" => $_GET,
                "Body" => json_decode(file_get_contents('php://input'), true)
            ];

        return $record;
        });

        $log->info($message, $params ?? []);

    }

    /**
     * Erros criticos do sistema 
     *  - Envia Mensagem para o telegram se estiver 'TELEGRAM' marcado como true 
     * @param string $message 
     * @param array $params
     * @todo arrumar essa bagunça
     */
    public static function critical(string $message, ?array $params = null)
    {
        $channel = getenv("CHANNEL");
        $logName = getenv("PROJECT");

        if(getenv("TELEGRAM")){
            $telegramText = 
            "*$logName [ $channel ]*\n
            *Mensagem:*```php " . $message . " ```
            *Data:*```php ". date("Y-m-d H:i:s") . " ```
            *Codigo:*```php " . http_response_code() . " ```
            *Rota:*```php " . $_GET['route'] . " ```";

            if(!is_null($params)){
                $telegramText .=  "*Dados:*```php ".
                 json_encode($params, 
                 JSON_UNESCAPED_UNICODE | 
                 JSON_UNESCAPED_SLASHES | 
                 JSON_PRETTY_PRINT) . "```";
            }

            (new Messanger)->send(str_replace(["    "], "",$telegramText));
        }

        $log = new Logger( $logName );
        $stream = new StreamHandler('log/critical.log', Logger::CRITICAL);
        $stream->setFormatter( LOG::formatter() );
        $log->pushHandler($stream);

        $log->pushProcessor(function ($record) {

            if(function_exists('apache_request_headers')){
                $headers = apache_request_headers();
                $record['extra']['client'] =
                [
                    "REMOTE_ADDR" =>  $_SERVER['REMOTE_ADDR'],
                    "CONTENT_TYPE" =>  $headers["Content-Type"] ?? "",
                    "USER_AGENT" => $headers["User-Agent"] ?? ""
                ];
                
            }

            if(Token::isInitialized()){
               $record["extra"]["user"]["USER_ID"] = Token::getUserId();
            }

           $record['extra']['request'] =
            [
                "Route" => $_GET,
                "Body" => json_decode(file_get_contents('php://input'), true)
            ];

            return $record;
        });
        $log->critical($message, $params ?? []);
    }


    private static function formatter()
    {
        $channel = getenv("CHANNEL");
        $dateFormat = "Y-m-d H:i:s";
        return new LineFormatter("[%datetime%] %channel%[$channel].%level_name% %message% %context% %extra%\n", $dateFormat);

    }

}