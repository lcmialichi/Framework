<?php

namespace Source\Curl;

final class CurlResponse
{
    public function __construct(
        private array $headers, 
        private array $curlInfo, 
        private string $response
        )
    {
    }

    /**
     * @return string
     */
    public function getResponse() : string
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * @return array|string
     */
    public function getInfo(?string $info = null) : array|string
    {
        if($info){
            return $this->curlInfo[$info];
        }
        return $this->curlInfo;
    }

    /**
     * #### Retorna true caso o response contenha a string informada
     * @return bool
     * @param string
     */
    public function contains(string $string) : bool
    {
        return str_contains($this->response, $string);
        
    }

    /**
     * ### Efetua recorte da pagina
     */
    public function cut(string $param1, string $param2)
    {
        $response = explode($param1,$this->response)[1];
        if($response){
            $response = explode($param2,$response)[0];
                if($response){
                    return new self($this->headers, $this->curlInfo, $response);
                }
        }

        return false;
    }

    /**
     * @return bool|array
     */
    public function explode($string) : bool|array
    {  
        $data = explode($string, $this->response);
        if(isset($data[1])){
            return array_map(function($data){
                return new self([], [], $data);
                }, $data
            );

        }
      
        return false;
          
    }


}