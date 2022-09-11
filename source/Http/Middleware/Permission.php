<?php

namespace Source\Http\Middleware;

use Source\Domain\Token;
use Source\Http\Response\Middleware;
use Source\Attributes\Permission as PermissionLevel;
use Source\Attributes\Response;
use Source\Router\Router;

/**
 * Middleware responsavel por validar as permissões do usuario interno
 */
#[Response(Middleware::class)]
class Permission {

        public function handle( Router $router ) 
        {

            $route = $router->route;
            if(!class_exists($route["handler"]) || !method_exists($route["handler"], $route["action"])){
                $router->redirect("/erro/404");

            }

            $reflectionClass = new \ReflectionClass($route["handler"]);
            $methode = $reflectionClass->getMethod($route['action']);
            $attribute = $methode->getAttributes("Source\Attributes\Permission");

            if($attribute){
                 $permissionsRequired = $attribute[0]->getArguments();
                 if(empty($permissionsRequired)){
                    return true;
                 }
; 
                $userPermissions = (array)Token::getPermissions();
                $permission = $userPermissions[$permissionsRequired["service"]]; 
                $permissionRequiredLevel = $permissionsRequired["level"] ?? PermissionLevel::NO_LEVEL;

                if(!is_null($permission)){
                    $permissionType = $permission[1];  #type
                    $permissionLevel = $permission[0]; #Level
                    
                    /**
                     * Valida o nivel de permissao do usuario
                     */
                    if( ((is_null($permissionLevel) || $permissionLevel < $permissionRequiredLevel) && $permissionRequiredLevel != PermissionLevel::NO_LEVEL) ){
                        $router->redirect("/erro/403");
                        return false;
                    }
    
                    $permissionTypeString = match($permissionType){
                        PermissionLevel::ALL  => "ALL",
                        PermissionLevel::HIERARCHY  => "HIERARCHY",
                        PermissionLevel::SELF  => "SELF",
                        PermissionLevel::ROBOT  => "ROBOT",
                        default => "SELF",
                    };
                    
                    return[ 
                        "type" => $permissionType,
                        "TypeString" => $permissionTypeString,
                        "level" => $permissionLevel
                     ];
                }

                $router->redirect("/erro/403");
                return false;
               
  
            }

            return true;
        }
}