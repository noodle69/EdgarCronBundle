<?php

namespace Edgar\CronBundle;

use Edgar\CronBundle\DependencyInjection\Compiler\CronPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarCronBundle extends Bundle
{
    /**
     * Build bundle.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CronPass());
    }
}
