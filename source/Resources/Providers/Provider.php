<?php

namespace Source\Resources\Providers;

use Source\Container\Container;

class Provider {

    final public function __construct(public Container $app){}
    
}