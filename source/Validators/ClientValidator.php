<?php

namespace Source\Validators;

class CLientValidator extends Validator {
    
    public function rules(): array {

        return [
            "cpf" => "Cpf|IntType"
        ];
    }
}