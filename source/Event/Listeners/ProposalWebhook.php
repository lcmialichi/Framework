<?php

namespace Source\Event\Listeners;

use Source\Exception\WebHookException;
use Source\Event\WebHook\Proposal as WebHook;
use Source\Event\Contracts\EventListenerInterface;
use Source\Event\Contracts\EventSubjectInterface;
use Source\Event\WebHook\Command\BashPrint;

class ProposalWebhook implements EventListenerInterface
{
    /**
     * @param Source\Event\Contracts\EventSubjectInterface $proposal
     * @return void
     */
    public function update(EventSubjectInterface $proposal): void
    {   
        if(!BashPrint::isInitialized()){
            BashPrint::initialize();
        }
        try {
            (new WebHook(
                proposalTransferObject: $proposal->proposalDTO,
                clientHash: $proposal->hashClient
            ))->submit();
            
            BashPrint::addSuccess();

        } catch (WebHookException) {
            BashPrint::addFail();
        }
    }
}
