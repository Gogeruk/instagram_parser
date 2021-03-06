<?php

namespace App\Controller;

use App\MessageBroker\RabbitMQ\Message\SampleMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class SampleController extends AbstractController
{

    /**
     * @Route("/sample", name="sample")
     */
    public function sample(MessageBusInterface $bus): Response
    {
        $message = new SampleMessage('content');
        $bus->dispatch($message);

        return new Response(sprintf('Message with content %s was published', $message->getContent()));
    }
}
