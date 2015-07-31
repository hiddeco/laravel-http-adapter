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
use HiddeCo\HttpAdapter\ConfigurationFactory;
use Ivory\HttpAdapter\Configuration;
use Ivory\HttpAdapter\ConfigurationInterface;
use Mockery;

class ConfigurationFactoryTest extends AbstractTestBenchTestCase
{
    public function testCreate()
    {
        $config = [
            'base_uri' => 'http://test.com'
        ];

        $factory = $this->getMockedFactory($config);

        $return = $factory->create($config);

        $this->assertInstanceOf(ConfigurationInterface::class, $return);
        $this->assertInstanceOf(Configuration::class, $return);
    }

    protected function getMockedFactory($config)
    {
        $configuration = Mockery::mock(Configuration::class.'[setBaseUri]');
        $configuration->shouldReceive('setBaseUri')->once()
            ->with('http://test.com');

        $mock = Mockery::mock(ConfigurationFactory::class.'[]', [$configuration]);

        return $mock;
    }
}