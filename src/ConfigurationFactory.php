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

use Ivory\HttpAdapter\Configuration;

class ConfigurationFactory
{
    /**
     * @var \Ivory\HttpAdapter\ConfigurationInterface
     */
    protected $configuration;

    /**
     * Create a new configuration factory instance.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Make a new configuration instance.
     *
     * @param array $config
     *
     * @return \Ivory\HttpAdapter\ConfigurationInterface
     */
    public function create(array $config)
    {
        foreach($config as $key => $value) {
            $this->configuration->{$this->getMethod($key)}($value);
        }

        return $this->configuration;
    }

    /**
     * Gets a method name.
     *
     * @param string $property
     *
     * @return string
     */
    protected function getMethod($property)
    {
        return 'set'.str_replace('_', '', $property);
    }
}
