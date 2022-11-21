<?php

namespace Source\Event;

use Source\Curl\Curl;
use Source\Event\WebHook\WebHookQueue;
use Spatie\Async\Pool;

class WebHookEvent extends ScriptEvent
{

    public string $url;
    public string $post;
    public int $id;
    public int $httpCode;
    public bool $success = false;
    public string $exception = "";

    /**
     *  {@inheritDoc} 
     */
    public function handle(): array
    {
        $queue = new WebHookQueue;
        $async = Pool::create();

        foreach ($queue->fetch() as $data) {

            $async->add(function () use ($data) {

                $response =  (new Curl)->post($data->url, $data->requisicao);
                $this->url = $data->url;
                $this->post =  $data->requisicao;
                $this->id = $data->id;
                $this->httpCode = $response->getInfo("http_code");

                if ($this->httpCode == 200) {
                    $this->success = true;
                    $this->notify();
                    return $data->id;
                }

                $this->success = false;
                $this->notify();
            })->catch(function ($exception) {
                $this->exception = $exception;
                $this->notify();
            });
        }

        $queue->delete($async->wait());
        return [
            "message" => "\033[42mFINALIZADO.\033[m",
        ];
    }
}
