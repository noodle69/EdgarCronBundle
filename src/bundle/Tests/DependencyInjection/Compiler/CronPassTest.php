<?php

namespace Edgar\CronBundle\Tests\DependencyInjection\Compiler;

use Edgar\CronBundle\DependencyInjection\Compiler\CronPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use EzSystems\EzPlatformAdminUi\Component\Registry;
use Symfony\Component\DependencyInjection\Reference;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;

/**
 * Class CronPassTest.
 */
class CronPassTest extends AbstractCompilerPassTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->setDefinition(Registry::class, new Definition());
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CronPass());
    }

    public function testProcess()
    {
        $taggedServiceId = 'collected_service';
        $collectedService = new Definition();
        $collectedService->addTag(CronPass::TAG_NAME, ['alias' => 'someAlias']);
        $this->setDefinition($taggedServiceId, $collectedService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            $taggedServiceId,
            CronPass::TAG_NAME,
            ['alias' => 'someAlias']
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            Registry::class,
            'addCron',
            ['someAlias', $taggedServiceId, new Reference($taggedServiceId)]
        );
    }

    public function testProcessWithNoGroup()
    {
        $taggedServiceId = 'collected_service';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Argument \'%s\' is invalid: Tag %s must contain "alias" argument.', $taggedServiceId, CronPass::TAG_NAME));

        $collectedService = new Definition();
        $collectedService->addTag(CronPass::TAG_NAME);
        $this->setDefinition($taggedServiceId, $collectedService);

        $this->compile();
    }
}
