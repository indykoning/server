<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\HTTP\Interceptor\JsonResourceInterceptor;
use App\Application\HTTP\Interceptor\StringToIntParametersInterceptor;
use App\Application\HTTP\Interceptor\UuidParametersConverterInterceptor;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;

final class AppBootloader extends DomainBootloader
{
    public function defineSingletons(): array
    {
        return [
            CoreInterface::class => fn(
                \Spiral\Core\Core $core,
                \Psr\Container\ContainerInterface $container,
                ?\Psr\EventDispatcher\EventDispatcherInterface $dispatcher = null,
            ): \Spiral\Core\InterceptableCore => self::domainCore($core, $container, $dispatcher),
        ];
    }

    protected static function defineInterceptors(): array
    {
        return [
            StringToIntParametersInterceptor::class,
            UuidParametersConverterInterceptor::class,
            JsonResourceInterceptor::class,
        ];
    }
}
