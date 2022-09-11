<?php

namespace Source\Domain;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Classe responsavel por acessar as informaçoes do token de autenticaçao
 */
class Token{
    
     /**
     * @var int
     */
    private static $userId;
    /**
     * @var int
     */
    private static $userAccessId;
    /**
     * @var array
     */
    private static $permissions;
    /**
     * @var string
     */
    private static $expireDate;
    /**
     * @var string
     */
    private static $accessDate;
    /**
     * @var boolean
     */
    private static $initialized = false;

    /**
     * Cria uma instancia de token e armazena suas informaçoes de forma estatica
     *
     * @return self
     */
    public static function build() : self
    {
        if(!Token::$initialized){
            $token = apache_request_headers()['Authorization'];          
            $jwt = JWT::decode(str_replace(["Bearer "], "",$token), new Key(getenv('JWTKEY'), 'HS256'));
            Token::$userId = $jwt->userId;
            Token::$userAccessId = $jwt->userAccessId;
            Token::$permissions = $jwt->permissions;
            Token::$expireDate = $jwt->expireDate;
            Token::$accessDate = $jwt->accessDate;
            Token::$initialized = true;
            return new self; 
        }

        throw new \Source\Exception\TokenError("Token ja inicializado");
    }

    /**
     * Retorna o id do usuario autenticado
     * @return int
     */
    public static function getUserId()
    {
        return self::$userId;
    }

     /**
     * Retorna o id de acesso do usuario autenticado
     * @return int
     */
    public static function getUserAccessId()
    {
        return self::$userAccessId;
    }

    /**
     * Retorna array de permissoes do usuario autenticado
     * @return array
     */
    public static function getPermissions()
    {
        return self::$permissions;
    }

    
    public static function getExpireDate()
    {
        return self::$expireDate;
    }

    public static function getAccessDate()
    {
        return self::$accessDate;
    }

    public static function isInitialized()
    {
        return self::$initialized;
    }
}