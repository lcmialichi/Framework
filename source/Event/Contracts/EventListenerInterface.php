<?php

namespace Source\Event\Contracts;

interface EventListenerInterface
{
    /**
     * Envia atualizaçoes de estado do objeto para os observadores
     *
     * @param EventSubjectInterface $subject
     * @return void
     */
    public function update(EventSubjectInterface $subject);
}