<?php

namespace Source\Api\Telegram;

class Messanger extends \Source\Curl\Curl {

    public function send ( string $message ) : void
    {
        $post = [
            "parse_mode" => "markdown",
            'chat_id' => getenv("TELEGRAM_CHAT_ID"),
            'text' =>  $message
        ];

        $this->post(url: TELEGRAM_URL . "/sendMessage", post: http_build_query($post));

    }

}