<?php

/*
 * This file is part of Laravel HTTP adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\HttpAdapter;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

class HttpAdapterManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \HiddeCo\HttpAdapter\HttpAdapterFactory
     */
    protected $factory;

    /**
     * Create a new HTTP adapter manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository
     * @param \HiddeCo\HttpAdapter\HttpAdapterFactory
     */
    public function __construct(Repository $config, HttpAdapterFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \Ivory\HttpAdapter\HttpAdapterInterface
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'httpadapter';
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getConnectionConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->config->get($this->getConfigName().'.connections');

        if (!is_array($config = array_get($connections, $name)) && !$config) {
            throw new \InvalidArgumentException(sprintf('Adapter [%s] is not configured', $name));
        }

        $config = array_merge($config, $this->getGlobalConfig());

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the global configuration.
     *
     * @return array
     */
    public function getGlobalConfig()
    {
        $global = array_only($this->config->get($this->getConfigName().'.global'), ['eventable', 'config']);

        if (array_key_exists('config', $global)) {
            $global['config'] = array_only($global['config'], [
                'protocol_version',
                'keep_alive',
                'encoding_type',
                'boundary',
                'timeout',
                'user_agent',
                'base_uri',
            ]);
        }

        return $global;
    }

    /**
     * Get the factory instance.
     *
     * @return \HiddeCo\HttpAdapter\HttpAdapterFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
