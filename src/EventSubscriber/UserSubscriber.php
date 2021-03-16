<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Service\ConfirmationTokenGenerator;
use App\Service\EmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $token = $this->confirmationTokenGenerator->generateToken($user->getEmail());
        $this->emailSender->sendEmail($user->getEmail(), $token);
    }
}
