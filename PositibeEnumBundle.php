<?php

namespace Positibe\Bundle\EnumBundle;

use Positibe\Bundle\EnumBundle\DependencyInjection\Compiler\ResourceServicesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeEnumBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ResourceServicesCompilerPass());
    }
}
