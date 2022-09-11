<?php

namespace Source\Permission;

/*
|--------------------------------------------------------------------------
|NIVEL DE PERMISSAO
|--------------------------------------------------------------------------
| Nao mexe aqui, so devem existir esses niveis
|
*/

interface PermissionLevelInterface{

const READ = 1;
const CREATE = 2;
const UPDATE = 3;
const DELETE = 4;
const NO_LEVEL = 5;



}