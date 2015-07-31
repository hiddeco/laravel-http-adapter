<?php

/*
 * This file is part of Laravel HTTP Adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\Tests\HttpAdapter;

use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher;
use HiddeCo\HttpAdapter\ConfigurationFactory;
use HiddeCo\HttpAdapter\HttpAdapterFactory;
use HiddeCo\HttpAdapter\HttpAdapterManager;
use Ivory\HttpAdapter\ConfigurationInterface;
use Ivory\HttpAdapter\EventDispatcherHttpAdapter;
use Ivory\HttpAdapter\HttpAdapterFactory as AdapterFactory;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Mockery;

class HttpAdapterFactoryTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        $config = [
            'adapter' => 'curl'
        ];

        $manager = Mockery::mock(HttpAdapterManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config);

        $this->assertInstanceOf(HttpAdapterInterface::class, $return);
    }

    public function testMakeWithConfiguration()
    {
        $config = [
            'adapter' => 'curl',
            'config'  => [
                'timeout' => 10
            ]
        ];

        $manager = Mockery::mock(HttpAdapterManager::class);

        $factory = $this->getMockedFactoryConfiguration($config, $manager);

        $return = $factory->make($config);

        $this->assertInstanceOf(HttpAdapterInterface::class, $return);
    }

    public function testMakeEventable()
    {
        $config = ['adapter' => 'curl', 'eventable' => true];

        $manager = Mockery::mock(HttpAdapterManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config);

        $this->assertInstanceOf(EventDispatcherHttpAdapter::class, $return);
        $this->assertInstanceOf(HttpAdapterInterface::class, $return);
    }

    public function testMakeWithConfigEventable()
    {
        $config = [
            'adapter'   => 'curl',
            'eventable' => true,
            'config'    => [
                'timeout' => 20
            ]
        ];

        $manager = Mockery::mock(HttpAdapterManager::class);

        $factory = $this->getMockedFactoryConfiguration($config, $manager);

        $return = $factory->make($config);

        $this->assertInstanceOf(EventDispatcherHttpAdapter::class, $return);
        $this->assertInstanceOf(HttpAdapterInterface::class, $return);
    }

    protected function getMockedFactory($config, $manager)
    {
        $adapter = Mockery::mock(AdapterFactory::class);
        $configuration = Mockery::mock(ConfigurationFactory::class);
        $dispatcher = Mockery::mock(AbstractEventDispatcher::class);

        $mock = Mockery::mock(HttpAdapterFactory::class.'[createAdapter,createConfiguration]', [$adapter, $configuration, $dispatcher]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)->andReturn(Mockery::mock(HttpAdapterInterface::class));

        return $mock;
    }

    protected function getMockedFactoryConfiguration($config, $manager)
    {
        $adapter = Mockery::mock(AdapterFactory::class);
        $configuration = Mockery::mock(ConfigurationFactory::class);
        $dispatcher = Mockery::mock(AbstractEventDispatcher::class);

        $configurationMock = Mockery::mock(ConfigurationInterface::class);

        $interface = Mockery::mock(HttpAdapterInterface::class.'[setConfiguration]');
        $interface->shouldReceive('setConfiguration')->once()
            ->with($configurationMock);

        $mock = Mockery::mock(HttpAdapterFactory::class.'[createAdapter,createConfiguration]', [$adapter, $configuration, $dispatcher]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)->andReturn($interface);

        $mock->shouldReceive('createConfiguration')->once()
            ->with($config['config'])->andReturn($configurationMock);

        return $mock;
    }
}