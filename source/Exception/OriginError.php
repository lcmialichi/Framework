<?php

namespace Source\Exception;

class OriginError extends \Error{
    
    public function __construct( string $message, int $code = 0, \Throwable $previous = null ) {
        parent::__construct("[ Origem ] " . $message, $code, $previous);
    }
}