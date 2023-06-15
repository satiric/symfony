<?php

declare(strict_types=1);

namespace App\Application\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

#[AsEventListener(event: ExceptionEvent::class)]
final class ExceptionEventListener
{
    public function __construct(
        private LoggerInterface $logger,
        private NormalizerInterface $normalizer,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws SerializerExceptionInterface
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = (array)$this->normalizer->normalize($event->getThrowable(), Throwable::class);
        $this->logger->error('Kernel exception event.', $exception);

        $code = $exception['code'] ?? Response::HTTP_INTERNAL_SERVER_ERROR;
        $format = $event->getRequest()->getPreferredFormat(default: JsonEncoder::FORMAT) ?? '';
        $context = [JsonEncode::OPTIONS => JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT];
        $response = new Response(
            content: $this->serializer->serialize($exception, $format, $context),
            status: is_int($code) ? $code : Response::HTTP_INTERNAL_SERVER_ERROR,
        );

        $event->setResponse($response);
    }
}
