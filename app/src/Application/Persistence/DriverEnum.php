<?php

namespace App\Application\Persistence;

use Spiral\Boot\EnvironmentInterface;
use Spiral\Boot\Injector\InjectableEnumInterface;
use Spiral\Boot\Injector\ProvideFrom;

#[ProvideFrom('detect')]
enum DriverEnum implements InjectableEnumInterface
{
    case InMemory;
    case Database;
    case MongoDb;

    public static function detect(EnvironmentInterface $env): self
    {
        return match ($env->get('PERSISTENCE_DRIVER', 'memory')) {
            'cache', 'memory', 'in-memory' => self::InMemory,
            'cycle', 'database', 'db' => self::Database,
            'mongodb', 'mongo' => self::MongoDb,
        };
    }
}