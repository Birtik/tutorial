<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


class EmailSender extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private \Swift_Mailer $swiftMailer;

    /**
     * @var string
     */
    private string $from;

    public function __construct(\Swift_Mailer $swiftMailer, string $from)
    {
        $this->from = $from;
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * @param string $mail
     * @param string $value
     */
    public function sendConfirmationEmail(string $mail, string $value): void
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($this->from)
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'registration/email_template.html.twig',
                    ['token' => $value]
                ),
                'text/html'
            );

        $this->swiftMailer->send($message);
    }

    /**
     * @param string $mail
     */
    public function sendDoubleRegistrationAlertEmail(string $mail): void
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                    'registration/email_template_alert.html.twig',
                ),
                'text/html'
            );

        $this->swiftMailer->send($message);
    }
}