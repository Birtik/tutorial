<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Service\ConfirmationTokenGenerator;
use App\Service\Email\EmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailSender
     */
    private EmailSender $emailSender;
    /**
     * @var ConfirmationTokenGenerator
     */
    private ConfirmationTokenGenerator $confirmationTokenGenerator;

    public function __construct(EmailSender $emailSender, ConfirmationTokenGenerator $confirmationTokenGenerator)
    {
        $this->emailSender = $emailSender;
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
        $this->emailSender->sendConfirmationEmail($user->getEmail(), $token->getValue());
    }
}
