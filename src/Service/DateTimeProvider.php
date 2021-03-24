<?php declare(strict_types=1);


namespace App\Service;


class DateTimeProvider implements DateTimeProviderInterface
{
    public function getCurrentTime(): int
    {
        return time();
    }

    public function getCurrentDateTime(): \DateTime
    {
        return new \DateTime();
    }
}