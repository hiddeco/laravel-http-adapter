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

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use HiddeCo\HttpAdapter\HttpAdapterServiceProvider;

abstract class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return HttpAdapterServiceProvider::class;
    }
}
