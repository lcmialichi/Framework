<?php

namespace Source\Exception;

class TabError extends \Error{

    public function __construct(string $message, int $code = 0, \Throwable $previous = null){
        parent::__construct( "[TAB] " . $message, $code, $previous);
    }

}
