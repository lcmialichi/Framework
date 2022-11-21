<?php

namespace Source\Validators;

use InvalidArgumentException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;
use Respect\Validation\Rules;

trait Validator
{
    private $stop = false;

    public function validate(array $fields): bool
    {
        if (!method_exists($this, "rules")) {
            return false;
        }

        foreach ($this->rules() as $key => $rule) {
            $allOf = $this->parse($key, $rule);
        }

        if (isset($allOf)) {
            foreach ($fields as $key => $value) {
                if (isset($allOf[$key])) {
                    $rules = $allOf[$key];
                    foreach ($rules as $rule) {
                        $validator = V::create()->addRule(
                            Factory::getDefaultInstance()->rule(
                                ucfirst($rule['rule']),
                                $this->replaceNulls($rule['args'])
                            )
                        )->validate($value);
              
                        if (!$validator) {
                            throw new InvalidArgumentException(
                                (new Message($key, $rule["rule"], $rule["args"]))->dispatch()
                            );
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * formata regras para determinada key de um array
     * 
     */
    private function parse(string $key, string $string): array|null
    {

        $rules = explode("|", $string) ?? [$string];
        $ruleCalls = [];
        foreach ($rules as $rule) {
            if ($this->mustBreak($rule) || $this->break()) {
                return null;
            }
            $mainRule = explode(":", $rule);
            $base = array_shift($mainRule);
            $args = is_null($mainRule[0]) ? [] : explode(",", $mainRule[0]);

            $ruleCalls[$key][$base] = [
                "rule" => $base,
                "args" => $args
            ];
        }

        return $ruleCalls;
    }


    private function break(): bool
    {
        return $this->stop;
    }

    /**
     * Atribuiçoes que encerram a validação ou
     * caso nao seja enviado parametro encerra.
     */
    private function mustBreak(string $ruleName)
    {
        return in_array(ucfirst($ruleName), ["AllowEmpty"]);
    }

    /**
     * Retorna os argumentos na formataçao correta para os parametros
     * da funcao de validaçao
     * 
     */
    private function replaceNulls(array $args)
    {
        return array_map(
            fn($arg) => $arg == "null" ? null : $arg, $args
        );
    }
}
