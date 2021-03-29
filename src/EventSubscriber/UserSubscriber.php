<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Service\ConfirmationTokenGenerator;
use App\Service\Email\EmailManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailManagerInterface
     */
    private EmailManagerInterface $emailManager;
    /**
     * @var ConfirmationTokenGenerator
     */
    private ConfirmationTokenGenerator $confirmationTokenGenerator;

    public function __construct(
        EmailManagerInterface $emailManager,
        ConfirmationTokenGenerator $confirmationTokenGenerator
    ) {
        $this->emailManager = $emailManager;
        $this->confirmationTokenGenerator = $confirmationTokenGenerator;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered',
        ];
    }

    /**
     * @param UserRegisteredEvent $event
     * @throws TransportExceptionInterface
     */
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        $token = $this->confirmationTokenGenerator->generateTokenForUser($user);
        $this->emailManager->sendConfirmationEmail($user->getEmail(), $token->getValue());
    }
}
