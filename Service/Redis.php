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

    private bool $connected = false;

    private array $closeHandlers = [];

    private string $connectionString;

    public function __construct(ContainerInterface $container, Loop $loop)
    {
        $this->container = $container;
        $this->loop = $loop->getLoop();
        $this->connectionString = $container->getParameter('react.redis.dsn');

        $factory = new Factory($this->loop);
        $this->client = $factory->createLazyClient($this->connectionString);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}