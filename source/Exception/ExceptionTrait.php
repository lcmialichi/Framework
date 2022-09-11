<?php

namespace Source\Exception;

trait ExceptionTrait{

    public static function invalidField( string $field) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        return new static("Campo '".$field."' possui valor inválido de entrada", $code->value);
    }

    public static function maxField( string $field, int $lenght) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        return new static("Campo '".$field."' ultrapassou tamanho limite (tamanho limite de caracteres: $lenght)", $code->value);
    }

    public static function minField( string $field, int $lenght) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        return new static("Campo '".$field."' não possui tamanho mínimo de entrada (tamanho mínimo de caracteres: $lenght)", $code->value);
    }

    public static function lenghtField( string $field, int $lenght) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        return new static("Campo '".$field."' deve ter o tamanho exato de entrada (tamanho exato de caracteres: $lenght)", $code->value);
    }

    public static function betweenField( string $field, int $lenght, int $lenght2) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        return new static("Campo '".$field."' deve ter o tamanho de caracteres entre $lenght e $lenght2 caracteres", $code->value);
    }

    public static function listValidFields( string $field, array $validFields ) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        $values = implode(", ", $validFields);
        return new static("Campo '".$field."' so permite os seguintes valores: [ $values ]", $code->value);
    }


}