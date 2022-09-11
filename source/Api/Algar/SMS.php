<?php 

namespace Source\Api\Algar;

class SMS {

    public static function send( string $msg, int $numero )
    {   

        $header = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('apppan:Mudar123'),
            'Accept: application/json'
        ];
    
        $zenviaid = getenv("ALGAR_PREFIXID") . "_" . uniqid();
        $post = [
            'bulkId' => 'CRM-DIGITA',
            'messages' => [
                [
                    'from' => 'CRMDIGITA',
                    'destinations' => [
                        [
                            'to' => "55" . $numero,
                            'messageId' => $zenviaid
                        ]
                    ],
                    'text' =>  $msg,
                    'flash'=>  false,
                ]
            ]
        ];

        $enviosimples = json_encode($post);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.messaging-service.com/sms/1/text/advanced');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $enviosimples);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $header);
        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);

    }

}