<?php

namespace Source\Container;

use Source\Resources\Providers\Provider;

class ContainerService
{
    public function loadContainers()
    {
        $resource = $this->getResources();
        array_walk($resource, function ($dir) {

            $dir = explode("/", $dir);
            $provider = trim(str_replace(".php", "", end($dir)));
            if ($instance =  resource("providers/{$provider}")) {
                if ($instance instanceof Provider) {
                    if (method_exists($instance, "register")) {
                        resolve(
                            $instance->{"register"}(...)
                        );
                    }
                }
            }
        });
    }

    public function getResources(): array
    {
        return resource("providers");
    }
}
