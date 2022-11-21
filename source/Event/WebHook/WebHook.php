<?php

namespace Source\Event\WebHook;

use Source\Curl\Curl;
use Spatie\Async\Pool;
use Source\Exception\WebHookException;
use Source\Model\WebHook as WebHookModel;
use Source\Model\WebHookQueue;

abstract class WebHook extends Curl
{

    protected array  $channels = [];
    private WebHookModel $webHookModel;

    public function __construct(string $service, $origin = null)
    {
        $this->webHookModel = new WebHookModel;
        $submitChanels = $this->webHookModel->findByService($service, true, $origin);
        if (!$submitChanels) {
            throw new WebHookException("Nenhum webhook cadastrado para o servico '$service'");
        }

        $this->channels = $submitChanels;
    }

    /**
     * Post formatado em array que sera enviado o webhook
     *
     * @return array
     */
    protected abstract function provider(): array;

    /**
     * Retorna os canais de envio 
     *
     * @return array
     */
    protected function getChanels(): array
    {
        return $this->channels;
    }

    /**
     *
     * @param integer $chanelId
     * @return void
     */
    protected function putIntoQueue( int $chanelId ) :void
    {
        (new WebHookQueue)->insert([
            "fila_id_webhook" => $chanelId,
            "fila_requisicao" => json_encode($this->provider())
        ])->execute();
    }

    /**
     *
     * @return void
     */
    public function putAllintoQueue( ) :void
    {
        foreach ($this->channels as $channel) {
            (new WebHookQueue)->insert([
                "fila_id_webhook" => $channel->id,
                "fila_requisicao" => json_encode($this->provider())
            ])->execute();
        }
    }

    /**
     * Envia WebHook
     *
     * @return void
     */
    final public function submit() : void
    {
        $async = Pool::create()
                ->timeout(30)
                ->concurrency(20);

        foreach ($this->channels as $channel) {
            $fnQueue = fn() => $this->putIntoQueue($channel->id);

            $async->add(function () use ($channel) {
                return $this->post(
                    url: $channel->url,
                    post: json_encode($this->provider())
                );

            })->then(function ($request) use ($fnQueue) {
                if ($request->getInfo("http_code") != 200) {
                    $fnQueue();
                }
   
            })->catch($fnQueue)->timeout($fnQueue);
        }

        $async->wait();
    }


}
