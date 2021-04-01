<?php declare(strict_types=1);

namespace App\Tests;

use App\Service\Email\EmailBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Address;

class EmailBuilderTest extends TestCase
{
    /**
     * @small
     */
    public function testBuildEmailWithDefaultFrom(): void
    {
        $defaultFrom = 'john@doe.pl';
        $to = 'jan@kowalski.pl';
        $subject = 'Test Subject';
        $template = 'templates/test_email.html.twig';
        $context = ['value' => 'lorem ipsum'];

        $builder = new EmailBuilder($defaultFrom);
        $testEmail = $builder
            ->createEmail()
            ->addTo($to)
            ->addSubject($subject)
            ->addHtmlTemplate($template)
            ->addContext($context)
            ->getEmail();


        self::assertEquals([new Address($defaultFrom)], $testEmail->getFrom());
        self::assertEquals([new Address($to)], $testEmail->getTo());
        self::assertSame($subject, $testEmail->getSubject());
        self::assertSame($template, $testEmail->getHtmlTemplate());
        self::assertSame($context, $testEmail->getContext());
    }

    /**
     * @small
     */
    public function testBuildEmailWithCustomFrom(): void
    {
        $defaultFrom = 'john@doe.pl';
        $customFrom = 'john@wick.pl';
        $to = 'jan@kowalski.pl';
        $subject = 'Test Subject';
        $template = 'templates/test_email.html.twig';
        $context = ['value' => 'lorem ipsum'];

        $builder = new EmailBuilder($defaultFrom);
        $testEmail = $builder
            ->createEmail()
            ->addFrom($customFrom)
            ->addTo($to)
            ->addSubject($subject)
            ->addHtmlTemplate($template)
            ->addContext($context)
            ->getEmail();

        self::assertEquals([new Address($customFrom)], $testEmail->getFrom());
        self::assertEquals([new Address($to)], $testEmail->getTo());
        self::assertSame($subject, $testEmail->getSubject());
        self::assertSame($template, $testEmail->getHtmlTemplate());
        self::assertSame($context, $testEmail->getContext());
    }

    /**
     * @small
     */
    public function testBuildEmailWithoutContext(): void
    {
        $defaultFrom = 'john@doe.pl';
        $to = 'jan@kowalski.pl';
        $subject = 'Test Subject';
        $template = 'templates/test_email.html.twig';

        $builder = new EmailBuilder($defaultFrom);
        $testEmail = $builder
            ->createEmail()
            ->addTo($to)
            ->addSubject($subject)
            ->addHtmlTemplate($template)
            ->getEmail();

        self::assertEquals([new Address($defaultFrom)], $testEmail->getFrom());
        self::assertEquals([new Address($to)], $testEmail->getTo());
        self::assertSame($subject, $testEmail->getSubject());
        self::assertSame($template, $testEmail->getHtmlTemplate());
        self::assertSame([], $testEmail->getContext());
    }
}