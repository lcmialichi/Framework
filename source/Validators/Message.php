<?php

namespace Source\Validators;


class Message
{

    private array $messages;

    public function __construct(
        private string $field,
        private string $rule,
        private array $arg = []
    ) {

        $this->messages = require_once __DIR__ . "/../Resources/Validation.php";
    }

    public function dispatch(): string
    {
        return $this->replace(
            $this->messages[$this->rule] ?? $this->default()
        );
    }

    private function replace(string $message)
    {
        return str_replace(
            [":attribute", ":arg1", ":arg2"],
            ["'$this->field'", $this->arg[0] ?? "?", $this->arg[1] ?? "?"],
            $message
        );
    }

    private function default()
    {
        return str_replace(
            ":attribute",
            "'$this->field'",
            "O campo :attribute não possui valor válido!"
        );
    }
}
