<?php

namespace Source\Event\WebHook;

use Source\Curl\Curl;
use Spatie\Async\Pool;
use Source\Exception\WebHookException;
use Source\Model\WebHook as WebHookModel;
use Source\Model\WebHookQueue;

abstract class WebHook extends Curl
{

    private array  $channels = [];
    private WebHookModel $webHookModel;

    public function __construct(string $service)
    {
        $this->webHookModel = new WebHookModel;
        $submitChanels = $this->webHookModel->findByService($service);
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

    protected function putIntoQueue( int $chanelId )
    {
        (new WebHookQueue)->insert([
            "fila_id_webhook" => $chanelId,
            "fila_requisicao" => json_encode($this->provider())
        ])->execute();
    }

    /**
     * Envia WebHook
     *
     * @return void
     */
    final public function submit()
    {
        $pool = Pool::create()->timeout(3);

        foreach ($this->channels as $channel) {
            
            $pool->add(function () use ($channel) {
                $request = $this->post(
                    url: $channel->url,
                    post: json_encode($this->provider())
                );

                if ($request->getInfo("http_code") != 200) {
                    $this->webHookModel->updateModel(
                        data: ["webhook_status" => 0],
                        id: $channel->id
                    );
                }
            })->timeout(function() use ($channel){
                $this->putIntoQueue($channel->id);
            });
        }

        $pool->wait();
    }
}
