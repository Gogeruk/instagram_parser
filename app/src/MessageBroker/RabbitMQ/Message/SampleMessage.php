<?php

namespace App\MessageBroker\RabbitMQ\Message;

class SampleMessage
{
    public function __construct(private string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}