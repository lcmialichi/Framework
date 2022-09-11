<?php

namespace Source\Event;

use Source\Event\Contracts\EventSubjectInterface;
use Source\Event\Contracts\EventListenerInterface;
use Source\Exception\ScriptError;

abstract class ScriptEvent implements EventSubjectInterface {

    use \Source\Script\ScriptTrait;

    /**
     * @var int 
     */
    public $state;

    /**
     * @var \SplObjectStorage 
     */
    private $listeners;

    public function __construct()
    {   
        $this->blockScriptFromRunnig();
        $this->listeners = new \SplObjectStorage();
    }

    abstract function handle(): void;

    public function attach(EventListenerInterface $listener): void
    {
        $this->listeners->attach($listener);
    }

    public function detach(EventListenerInterface $listener): void
    {
        $this->listeners->detach($listener);
    }

    public function notify(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->update($this);
        }
    }


}
