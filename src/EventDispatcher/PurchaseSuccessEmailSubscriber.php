<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            'purchase.success' =>'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent  $event)
    {

    }


}