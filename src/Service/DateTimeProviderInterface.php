<?php


namespace App\Service;


interface DateTimeProviderInterface
{
    public function getCurrentTime();

    public function getCurrentDateTime();
}