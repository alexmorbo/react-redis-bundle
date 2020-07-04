<?php

declare(strict_types=1);

namespace Morbo\React\Redis;

use Morbo\React\Loop\DependencyInjection\ReactLoopExtension;
use Morbo\React\Redis\DependencyInjection\ReactRedisExtension;
use Morbo\React\Redis\Service\Redis;
use React\Promise\Promise;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReactRedisTest extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testDependencyInjection()
    {
        $this->assertTrue(self::$container->has('react.redis'), '"react.redis" is loaded');
        $this->assertTrue(self::$container->has(Redis::class), '"Redis::class" is loaded');
    }

    public function testParametersBag()
    {
        $this->assertIsScalar(self::$container->getParameter('react.redis.dsn'));
    }

    public function testPromise()
    {
        $container = new ContainerBuilder();

        $loopExtension = new ReactLoopExtension();
        $loopExtension->load([], $container);

        $extension = new ReactRedisExtension();
        $extension->load([], $container);

        /** @var Redis $redis */
        $redis = $container->get('react.redis');

        $promise = new Promise(
            function () {
            }
        );

        $this->assertEquals(get_class($promise), get_class($redis->ready()));
    }
}
