<?php

namespace App\Application\Shared\Bus;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
}
