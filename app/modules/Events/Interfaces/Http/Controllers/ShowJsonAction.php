<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Application\Commands\FindEventByUuid;
use App\Application\Domain\ValueObjects\Uuid;
use App\Application\Exception\EntityNotFoundException;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Http\Exception\ClientException\NotFoundException;
use Spiral\Router\Annotation\Route;

class ShowJsonAction
{
    #[Route(route: '/event/{uuid}/json', name: 'event.show.json', group: 'api')]
    public function __invoke(QueryBusInterface $bus, Uuid $uuid)
    {
        try {
            return $bus->ask(new FindEventByUuid($uuid));
        } catch (EntityNotFoundException $e) {
            throw new NotFoundException($e->getMessage());
        }
    }
}
