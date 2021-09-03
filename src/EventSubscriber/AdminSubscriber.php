<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\UserRegisteredEvent;

class AdminSubscriber implements EventSubscriberInterface
{
    public function onUserCreated(UserRegisteredEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.created' => 'onUserCreated',
        ];
    }
}
