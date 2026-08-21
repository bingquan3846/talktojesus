<?php

namespace Bao;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class BaoBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

    }
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
