<?php

namespace Source\Event\WebHook;

use Generator;
use Source\Model\WebHookQueue as QueueModel;

class WebHookQueue
{
    /**
     * @var integer
     */
    public int $position;
    /**
     * @var object
     */
    public object $current;
    /**
     * @var array
     */
    private $array;
    /**
     * Id da requisicao atual
     *
     * @var integer
     */
    public int $currentId;


    public function __construct()
    {
        $this->webHookModel = new QueueModel;
        $this->array = $this->webHookModel->fetchAll();
        $this->rewind();
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Retorna o objeto configurado com o item da fila atual
     *
     * @return Generator
     */
    public function fetch(): Generator
    {
        for ($this->key(); $this->valid(); $this->next()) {
            yield $this->current();
        }

        return false;
    }

    public function valid(): bool
    {
        return isset($this->array[$this->position]);
    }

    private function current(): mixed
    {
        return $this->array[$this->position];
    }

    public function delete(array $idList): bool
    {
        return $this->webHookModel->delete()->whereIn("fila_id", $idList)->execute();
    }
}
