<?php

declare(strict_types=1);

namespace Morbo\React\Redis\Tests;

use Morbo\React\Loop\ReactLoopBundle;
use Morbo\React\Redis\ReactRedisBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new ReactRedisBundle(),
            new ReactLoopBundle(),
        ];
    }

    public function getCacheDir()
    {
        return __DIR__.'/cache/'.spl_object_hash($this);
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}