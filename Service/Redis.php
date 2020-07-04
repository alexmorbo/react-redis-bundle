<?php

declare(strict_types=1);

namespace Morbo\React\Redis\Service;

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use Exception;
use Morbo\React\Loop\Service\Loop;
use Morbo\React\Loop\Service\LoopAwareTrait;
use Morbo\React\Redis\Exception\RedisException;
use React\Promise\PromiseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

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
    }

    public function addCloseHandler(callable $onError): void
    {
        $this->closeHandlers[] = $onError;
    }

    public function ready(): PromiseInterface
    {
        if ($this->connected) {
            return resolve($this->client);
        }

        $factory = new Factory($this->loop);

        return $factory->createClient($this->connectionString)->then(
            function (Client $client) {
                $this->connected = true;
                $this->client = $client;

                $client->on(
                    'error',
                    function (Exception $e) {
                        throw new RedisException('React Redis error: '.$e->getMessage());
                    }
                );

                $client->on(
                    'close',
                    function () {
                        $this->connected = false;
                        $this->client = null;
                        foreach ($this->closeHandlers as $closeHandler) {
                            call_user_func($closeHandler);
                        }
                    }
                );

                return resolve($this->client);
            },
            function (Exception $e) {
                return reject($e);
            }
        );
    }
}