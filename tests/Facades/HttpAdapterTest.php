<?php

/*
 * This file is part of Laravel HTTP Adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\Tests\HttpAdapter\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use HiddeCo\HttpAdapter\Facades\HttpAdapter;
use HiddeCo\HttpAdapter\HttpAdapterManager;
use HiddeCo\Tests\HttpAdapter\AbstractTestCase;

class HttpAdapterTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'httpadapter';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    public function getFacadeClass()
    {
        return HttpAdapter::class;
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return HttpAdapterManager::class;
    }
}
