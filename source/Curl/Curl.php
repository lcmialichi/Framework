<?php

namespace Source\Curl;

use \Source\Curl\CurlResponse;

/*
|--------------------------------------------------------------------------
| CURL HTTP/HTTPS REQUEST
|--------------------------------------------------------------------------
|Voce nunca deve mexer nesta classe, mas voce pode instanciar ou estende-la 
|em suas classes para consumir APIs ou até mesmo desenvolver RPAs :)
|leia um pouco do codigo para entender as funcionalidades
|
*/

class Curl {

    /**
     * @var array
     */
    private array $curlOptions = [
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_ENCODING => "", 
        CURLOPT_CONNECTTIMEOUT => 250, 
        CURLOPT_TIMEOUT => 250, 
        CURLOPT_MAXREDIRS => 10, 
        CURLOPT_SSL_VERIFYPEER => false, 
    ];

    /**
     * Configuracoes extras forçadas
     * @var array
     */
    private array $extrasForced = [];
    /**
     * @var string
     */
    private string $cookie ;
    /**
     * Formatador de post
     * @var \closure
     */
    private \closure $formatter;

    /**
     * Destroi o cookie caso esteja sendo usado
     */
    public function __destruct()
    {
        if(isset($this->cookie) && file_exists($this->cookie)){
            unlink($this->cookie);
            unset($this->cookie);
        }
    }

    /**
     * Metodo Post para requisicao
     * @param string|array $post
     * @param array|null $header
     * @param string $url
     * @return CurlResponse
     */
    public function post(string $url, mixed $post, ?array $header = null) : CurlResponse
    {   
        if(isset($this->formatter)){
            $formatter = $this->formatter;
            $post = $formatter($post);
        }

        $opt = array_replace(
            $this->curlOptions,
            [
                CURLOPT_HEADER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_HTTPHEADER => $header ?? [],
                CURLOPT_URL => $url
            ],
            $this->extrasForced
       );

        $ch = curl_init();
        curl_setopt_array($ch, $opt);
        $response = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);
        $header = $this->headersToArray( substr($response, 0, $curlInfo['header_size']) );
        $content = substr($response, $curlInfo['header_size']);

        return new CurlResponse(
            $header,$curlInfo, $content
        );

    }

    /**
     * Metodo Get para requisicao
     * @param array|null $header
     * @param string $url
     * @return CurlResponse
     */
    public function get( string $url, ?array $header = null ) : CurlResponse
    {

        $opt = array_replace( 
            $this->curlOptions,
            [
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HEADER => true, 
                CURLOPT_HTTPHEADER => $header ?? [],
                CURLOPT_URL => $url
            ],
            $this->extrasForced
        );
   
        $ch = curl_init();
        curl_setopt_array($ch, $opt);
        $response = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);
        $header = $this->headersToArray( substr($response, 0, $curlInfo['header_size']) );
        $content = substr($response, $curlInfo['header_size']);

        return new CurlResponse(
            $header,$curlInfo, $content
        );
    
    } 

    /**
     * Modifica Configuraçoes customizadas nas requisicoes
     * @param string[] $options
     * @return self
     */
    public function setOpt(array $options) : self
    {
       $this->curlOptions = array_replace($this->curlOptions, $options);
       return $this;

    }

    /**
     * Remove Configuracoes das requisicoes, caso nao seja enviado nenhum parametro
     * reseta as configuraçoes do Curl
     * @param string $options
     * @return self
     */
    public function unsetOpt( ?string ...$options ) : self
    {
        if($options){
            foreach($options as $option){
                unset($this->curlOptions[$option]);
            }
            return $this;
        }

        unset($this->curlOptions);
        $this->curlOptions = [
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_FOLLOWLOCATION => true, 
            CURLOPT_ENCODING => "", 
            CURLOPT_CONNECTTIMEOUT => 250, 
            CURLOPT_TIMEOUT => 250, 
            CURLOPT_MAXREDIRS => 10, 
            CURLOPT_SSL_VERIFYPEER => false, 
        ];
        return $this;

    }

    /**
     * Implementa a utilizacao de cookies nas requisiçoes
     * @param bool $useCookie
     * @return self
     */
    public function useCookie(bool $useCookie = true) : self
    {
        if($useCookie){
            if(!isset($this->cookie)){
                $this->cookie = realpath(".") . "/cookies/" . uniqid("CURL_");

            }

            $this->extrasForced += [
                CURLOPT_COOKIEJAR => $this->cookie,
                CURLOPT_COOKIEFILE => $this->cookie
            ];
  
            return $this;

        }
        unset(
            $this->extrasForced[CURLOPT_COOKIEJAR],
            $this->extrasForced[CURLOPT_COOKIEFILE]
        );

        Curl::__destruct();
        return $this;
      
    }

    /**
     * Define uma formatacao para o post.
     *  Exemplo: 
     * 
     *  ```php
     * $curl->setPostFormatter( fn($post) => json_encode($post) ); 
     * ```
     * @return Curl
     */
    public function setPostFormatter(\Closure $formatter) : self
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Remove formatador de post
     */
    public function unsetPostFormatter() : void
    {
        if(isset($this->formatter)){
            unset($this->formatter);
        }
       
    }
    
    private function headersToArray( string $str )
    {   
        $headers = array();
        $headersTmpArray = explode( "\r\n" , $str );
        for ( $i = 0 ; $i < count( $headersTmpArray ) ; ++$i )
        {
            if ( strlen( $headersTmpArray[$i] ) > 0 )
            {
                if ( strpos( $headersTmpArray[$i] , ":" ) )
                {
                    $headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
                    $headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+ 1 );
                    $headers[$headerName] = $headerValue;
                }
            }
        }
        return $headers;
    }

    public function recaptchaSolve(string $url, string $sitekey)
    {
        return ( new \Source\Curl\Captcha )->recaptchaSolve($url, $sitekey);

    }

}



