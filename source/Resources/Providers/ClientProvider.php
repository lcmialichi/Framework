<?php

namespace Source\Resources\Providers;

use Source\Model;
use Source\DTO\Client;
use Source\Request\User;
use Source\Request\Request;
use Source\Container\Container;
use Source\Model\ClientContact;
use Source\Model\Client as ClientModel;

class ClientProvider extends Provider
{

    public function register(Request $request)
    {          
        if ($request->hashClient) {
            $client = (new ClientModel())->findIdByHash(
                $request->hashClient
            );
    
            if (!$client) {
                throw new \InvalidArgumentException("hash informada nao possui cadastro!");
            }

            $this->app->bind(Client::class, function () use ($client, $request) {

                $personal = (new Model\CLient)->getClient($request->hashClient);
                $address = (new Model\ClientAddress)->getClient($client->id);
                $bank = (new Model\ClientBank)->getClient($client->id);
                $bankEndorsement = (new Model\ClientBank)->getClient($client->id, 1);
                $contact = (new ClientContact)->getClient($client->id);
                $document = (new Model\ClientDocument)->getClient($client->id);
                $employer =  (new Model\ClientEmployer)->getClient($client->id);
                $client = Client::make(
                    personal: $personal,
                    address: $address,
                    bank: $bank,
                    bankEndorsement: $bankEndorsement,
                    contact: $contact,
                    document: $document,
                    employer: $employer
                );
                
                return $client;
            });
        }
    }
}
