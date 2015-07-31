<?php

/*
 * This file is part of Laravel HTTP Adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\HttpAdapter;

use HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher;
use Ivory\HttpAdapter\EventDispatcherHttpAdapter;
use Ivory\HttpAdapter\HttpAdapterFactory as AdapterFactory;

class HttpAdapterFactory
{
    /**
     * The adapter factory instance.
     *
     * @var \Ivory\HttpAdapter\HttpAdapterFactory
     */
    protected $adapter;

    /**
     * The configuration factory instance.
     *
     * @var \HiddeCo\HttpAdapter\ConfigurationFactory
     */
    protected $configuration;

    /**
     * The event dispatcher instance.
     *
     * @var \HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Create a new HTTP adapter factory instance.
     *
     * @param \Ivory\HttpAdapter\HttpAdapterFactory
     * @param \HiddeCo\HttpAdapter\ConfigurationFactory
     */
    public function __construct(AdapterFactory $adapter, ConfigurationFactory $configuration, AbstractEventDispatcher $eventDispatcher)
    {
        $this->adapter = $adapter;
        $this->configuration = $configuration;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * Make a new Ivory HTTP adapter instance.
     *
     * @param array $config
     *
     * @return \Ivory\HttpAdapter\HttpAdapterInterface
     */
    public function make(array $config)
    {
        $adapter = $this->createAdapter($config);

        if(is_array($parameters = array_get($config, 'config'))) {
            $configuration = $this->createConfiguration($parameters);
            $adapter->setConfiguration($configuration);
        }

        if(array_get($config, 'eventable', false)) {
            return new EventDispatcherHttpAdapter($adapter, $this->eventDispatcher);
        }

        return $adapter;
    }

    /**
     * Establish an adapter connection.
     *
     * @param array $config
     *
     * @return \Ivory\HttpAdapter\HttpAdapterInterface
     */
    public function createAdapter(array $config)
    {
        return $this->adapter->create($config['adapter']);
    }


    /**
     * Establish an configuration.
     *
     * @param array $config
     *
     * @return \Ivory\HttpAdapter\ConfigurationInterface
     */
    public function createConfiguration(array $config)
    {
        return $this->configuration->create($config);
    }
}