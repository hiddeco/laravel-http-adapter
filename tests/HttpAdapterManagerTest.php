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
use HiddeCo\HttpAdapter\HttpAdapterFactory;
use HiddeCo\HttpAdapter\HttpAdapterManager;
use Illuminate\Config\Repository;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Mockery;

class HttpAdapterManagerTest extends AbstractTestBenchTestCase
{
    public function testConnectionName()
    {
        $config = ['adapter' => 'curl'];

        $manager = $this->getConfigManager($config);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('curl');

        $this->assertInstanceOf(HttpAdapterInterface::class, $return);

        $this->assertArrayHasKey('curl', $manager->getConnections());
    }

    public function testConnectionNull()
    {
        $config = ['adapter' => 'curl'];

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.default')->andReturn('curl');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf(HttpAdapterInterface::class, $return);

        $this->assertArrayHasKey('curl', $manager->getConnections());
    }

    public function testConnectionGlobal()
    {
        $config = ['adapter' => 'curl', 'config' => ['base_uri' => 'http://test.com']];

        $global = ['config' => ['timeout' => 10]];

        $manager = $this->getConfigManagerGlobal($config, $global);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('curl');

        $this->assertInstanceOf(HttpAdapterInterface::class, $return);

        $this->assertArrayHasKey('curl', $manager->getConnections());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Adapter [exception] is not configured
     */
    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = ['adapter' => 'exception'];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.connections')->andReturn(['curl' => $config]);

        $this->assertSame([], $manager->getConnections());

        $manager->connection('exception');
    }

    protected function getManager()
    {
        $config = Mockery::mock(Repository::class);
        $factory = Mockery::mock(HttpAdapterFactory::class);

        return new HttpAdapterManager($config, $factory);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.connections')->andReturn(['curl' => $config]);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.global')->andReturn([]);

        $config['name'] = 'curl';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(HttpAdapterInterface::class));

        return $manager;
    }

    protected function getConfigManagerGlobal(array $config, array $global)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.connections')->andReturn(['curl' => $config]);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('httpadapter.global')->andReturn($global);

        $config = array_merge($config, $global);
        $config['name'] = 'curl';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(HttpAdapterInterface::class));

        return $manager;
    }
}
