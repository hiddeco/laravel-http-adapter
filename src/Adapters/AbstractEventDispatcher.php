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

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface.
 *
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher when dispatching events.
 */
abstract class AbstractEventDispatcher implements SymfonyDispatcher
{
    /**
     * The Laravel Events Dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|\Illuminate\Events\Dispatcher
     */
    protected $laravelDispatcher;

    /**
     * The Symfony Event Dispatcher.
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $symfonyDispatcher;

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param string $eventName The name of the event to dispatch. The name of
     *                          the event is the name of the method that is
     *                          invoked on listeners.
     * @param Event  $event     The event to pass to the event handlers/listeners.
     *                          If not supplied, an empty Event instance is created.
     *
     * @return Event
     */
    public function dispatch($eventName, Event $event = null)
    {
        if ($event === null) {
            $event = new Event();
        }

        $event->setName($eventName);
        $event->setDispatcher($this);

        $this->laravelDispatcher->fire($eventName, $event);
        $this->symfonyDispatcher->dispatch($eventName, $event);

        $event->setDispatcher($this);

        return $event;
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string   $eventName The event to listen on
     * @param callable $listener  The listener
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain.
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->symfonyDispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * Adds an event subscriber.
     *
     * The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     *
     * @param EventSubscriberInterface $subscriber The subscriber.
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->addSubscriber($subscriber);
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param string   $eventName           The event to remove a listener from
     * @param callable $listenerToBeRemoved The listener to remove
     */
    public function removeListener($eventName, $listenerToBeRemoved)
    {
        $this->symfonyDispatcher->removeListener($eventName, $listenerToBeRemoved);
    }

    /**
     * Removes an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber The subscriber
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->removeSubscriber($subscriber);
    }

    /**
     * Gets the listeners of a specific event or all listeners.
     *
     * @param string $eventName The name of the event
     *
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners($eventName = null)
    {
        return $this->symfonyDispatcher->getListeners($eventName);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName The name of the event
     *
     * @return bool true if the specified event has any listeners, false otherwise
     */
    public function hasListeners($eventName = null)
    {
        return ($this->symfonyDispatcher->hasListeners($eventName) ||
            $this->laravelDispatcher->hasListeners($eventName));
    }
}
