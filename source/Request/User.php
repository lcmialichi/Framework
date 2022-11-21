<?php

namespace Source\Request;

class User
{

    public function __construct(
        private $id = null,
        private $type = null,
        private $level = null
    ) {
    }

    public function id()
    {
        return $this->id;
    }

    public function type()
    {
        return $this->type;
    }

    public function level()
    {
        return $this->level;
    }
}
