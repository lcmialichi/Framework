<?php

namespace Source\Attributes;

use \Source\Permission\PermissionLevelInterface as Level;
use \Source\Permission\PermissionTypeInterface as Type;
use \Source\Permission\PermissionServiceInterface as Service;
/**
 * Informa qual tipo de permissao o usuario precisa ter para acessar o controller
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Permission implements Level, Type, Service
{}

