<?php

declare(strict_types=1);

namespace App\Application\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[AsEventListener(event: ResponseEvent::class)]
final class ResponseEventListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $requestId = $event->getRequest()->headers->get(key: 'X-Request-Id');
        $event->getResponse()->headers->add(['X-Request-Id' => $requestId]);

        if ($event->getRequest()->getPreferredFormat(default: JsonEncoder::FORMAT) === JsonEncoder::FORMAT) {
            $event->getResponse()->headers->add(['Content-Type' => 'application/json']);
        }
    }
}