<?php

namespace Source\Event\Contracts;

interface EventSubjectInterface
{
    /**
     * Atrela ao evento um observador
     *
     * @param EventListenerInterface $observer
     * @return void
     */
    public function attach(EventListenerInterface $observer): void;
  
    /**
     * Desatrela um observador do evento
     *
     * @param EventListenerInterface $observer
     * @return void
     */
    public function detach(EventListenerInterface $observer): void;
   
    /**
     * Notifica todos os objetos atrelados ao evento
     *
     * @return void
     */
    public function notify(): void;

    /**
     * Executa o script do evento
     *
     * @return void
     */
    public function handle() : array;
}