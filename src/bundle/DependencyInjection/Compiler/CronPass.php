<?php

namespace Edgar\CronBundle\DependencyInjection\Compiler;

use Edgar\Cron\Handler\CronHandler;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CronPass.
 */
class CronPass implements CompilerPassInterface
{
    const TAG_NAME = 'edgar.cron';

    /**
     * Fetch all cron by tag edgar:cron.
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CronHandler::class)) {
            return;
        }

        $definition = $container->findDefinition(CronHandler::class);

        $taggedServices = $container->findTaggedServiceIds(self::TAG_NAME);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    throw new InvalidArgumentException($id, 'Tag ' . self::TAG_NAME . ' must contain "alias" argument.');
                }

                $definition->addMethodCall('addCron', [
                    new Reference($id),
                    $attributes['alias'],
                    !isset($attributes['priority']) ? 0 : (int)$attributes['priority'],
                    !isset($attributes['expression']) ? '* * * * *' : $attributes['expression'],
                    !isset($attributes['arguments']) ? '' : $attributes['arguments'],
                ]);
            }
        }
    }
}
