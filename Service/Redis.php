<?php

declare(strict_types=1);

namespace Morbo\React\Redis\Service;

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use Morbo\React\Loop\Service\Loop;
use Morbo\React\Loop\Service\LoopAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Redis
{
    use LoopAwareTrait;

    protected ContainerInterface $container;

    private ?Client $client;

    private string $connectionString;

    public function __construct(ContainerInterface $container, Loop $loop, string $connectionString)
    {
        $this->container = $container;
        $this->loop = $loop->getLoop();

        $factory = new Factory($this->loop);
        $this->client = $factory->createLazyClient($connectionString);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}