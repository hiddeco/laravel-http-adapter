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

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher;
use HiddeCo\HttpAdapter\ConfigurationFactory;
use HiddeCo\HttpAdapter\HttpAdapterFactory;
use HiddeCo\HttpAdapter\HttpAdapterManager;
use Ivory\HttpAdapter\HttpAdapterFactory as AdapterFactory;
use Ivory\HttpAdapter\HttpAdapterInterface;

class HttpAdapterServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAdapterFactoryIsInjectable()
    {
        $this->assertIsInjectable(AdapterFactory::class);
    }

    public function testConfigurationFactoryIsInjectable()
    {
        $this->assertIsInjectable(ConfigurationFactory::class);
    }

    public function testHttpAdapterFactoryIsInjectable()
    {
        $this->assertIsInjectable(HttpAdapterFactory::class);
    }

    public function testHttpAdapterManagerIsInjectable()
    {
        $this->assertIsInjectable(HttpAdapterManager::class);
    }

    public function testBindings()
    {
        $this->assertIsInjectable(HttpAdapterInterface::class);

        $original = $this->app['httpadapter.connection'];
        $this->app['httpadapter']->reconnect();
        $new = $this->app['httpadapter.connection'];

        $this->assertNotSame($original, $new);
        $this->assertEquals($original, $new);
    }
}