<?php

/*
 * This file is part of Laravel HTTP Adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>, Mark Redeman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\HttpAdapter\Adapters;

use Illuminate\Contracts\Events\Dispatcher as LaravelDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface.
 *
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher when dispatching events.
 */
class EventDispatcherLaravel extends AbstractEventDispatcher
{
    /**
     * Forward all methods to the Laravel Events Dispatcher.
     *
     * @param LaravelDispatcher $laravelDispatcher
     * @param SymfonyDispatcher $symfonyDispatcher
     */
    public function __construct(LaravelDispatcher $laravelDispatcher, SymfonyDispatcher $symfonyDispatcher)
    {
        $this->laravelDispatcher = $laravelDispatcher;
        $this->symfonyDispatcher = $symfonyDispatcher;
    }
}
