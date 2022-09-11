<?php

namespace Source\Model\ValueObject;

/***
 * Encapsulamento das permissoes do usuario
 */
class SimplifyedPermissionObject
{
    private $permissionByType;

    public function __construct(?array $permissions) 
    {       
        $permissionByType = [];
        foreach( $permissions as $permission ){
            $permissionByType[$permission->simplificada_origem][$permission->simplificada_id_permissao]["id"] = $permission->simplificada_id_permissao;
            $permissionByType[$permission->simplificada_origem][$permission->simplificada_id_permissao]["level"] = $permission->simplificada_nivel;
            $permissionByType[$permission->simplificada_origem][$permission->simplificada_id_permissao]["type"] = $permission->simplificada_tipo;
            $permissionByType[$permission->simplificada_origem][$permission->simplificada_id_permissao]["active"] = $permission->simplificada_ativo;

        }
        $this->permissionByType = $permissionByType;
    }

    /**
     * Retorna as permissoes de usuario de forma simplificada idependente de estar ativa ou inativa
     * separadas por tipo de permissao
     * @return array
     */
    public function getAllPermission()
    {
        return $this->permissionByType;
    }

    /**
     * Retorna as permissoes do usuario ativas
     * aqui é aplicado a regra de negocio (Permissoes do usuario sobrepoe as permissoes as de Perfil)
     */
    public function getPermissions() : null|array
    {
        if($this->permissionByType){
            /** Sobrepoem as permissoes de Perfil pelo de usuario */
            if(isset($this->permissionByType["Usuario"]) && isset($this->permissionByType["Perfil"])){ #se existe permissoes de usuario e de perfil
                foreach($this->permissionByType["Perfil"] as $value){
                    $fullPermission[$value["id"]] = [
                       "level" => $value["level"], 
                       "type" => $value["type"], 
                       "active" => $value["active"]
                    ];
                    
                }
                foreach($this->permissionByType["Usuario"] as  $value){
                    $fullPermission[$value["id"]] = [
                        "level" => $value["level"], 
                        "type" =>  $value["type"], 
                        "active" => $value["active"]
                        ];
    
                }
            }else if ($this->permissionByType["Usuario"]){ # se existe apenas permissoes de usuario
                $fullPermission = $this->permissionByType["Usuario"];

            }else if ($this->permissionByType["Perfil"]){# se existe apenas permissoes de perfil
                $fullPermission = $this->permissionByType["Perfil"];

            }else{
                $fullPermission = [];
            }

            /** Filtra as permissoes ativas*/
            $userPermissions = array_filter($fullPermission, function($value){
                return $value['active'] === 1 ? true : false;
            });

            return  $userPermissions;

        }

        return $userPermissions ?? null;

    }
}