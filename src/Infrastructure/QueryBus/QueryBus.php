<?php
namespace App\Infrastructure\QueryBus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class QueryBus implements QueryBusInterface
{
    public function __construct(
        #[Autowire(service: 'query.bus')]
        private MessageBusInterface $bus,
    ) {
    }

    public function ask(object $query): mixed
    {
        return $this->bus
            ->dispatch($query)
            ->last(HandledStamp::class)
            ->getResult();
    }
}