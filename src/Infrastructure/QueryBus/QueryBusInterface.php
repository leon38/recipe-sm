<?php
namespace App\Infrastructure\QueryBus;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
}