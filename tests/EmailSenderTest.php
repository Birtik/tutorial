<?php declare(strict_types=1);


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailSenderTest extends WebTestCase
{
    public function testSend()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/sind');
        //$mailCollector = $client->getProfile()->getCollector('swiftmailer');

        //$this->assertSame(1, $mailCollector->getMessageCount());
    }
}