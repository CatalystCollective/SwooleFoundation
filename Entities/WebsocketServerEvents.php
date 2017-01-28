<?php
/**
 * This file is part of the Catalyst Swoole Foundation.
 *
 * (c)2017 Matthias Kaschubowski and the Catalyst Collective
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Catalyst\Swoole\Entities;


use Catalyst\Swoole\EventsEntityInterface;

/**
 * Class WebsocketServerEvents
 *
 * @package Catalyst\Swoole\Entities
 */
class WebsocketServerEvents implements EventsEntityInterface
{
    /**
     * @var array
     */
    private $events = [];
    /**
     * @var array
     */
    private static $eventNames = [
        'onStart',
        'onShutdown',
        'onWorkerStart',
        'onWorkerEnd',
        'onWorkerStop',
        'onTimer',
        'onTask',
        'onFinish',
        'onPipeMessage',
        'onRequest',
        'onWorkerError',
        'onManagerStart',
        'onManagerStop',
    ];

    /**
     * enqueues a callback to the onRequest Event
     *
     * @param callable $callback
     */
    public function onRequest(callable $callback)
    {
        $this->events['request'][] = $callback;
    }

    /**
     * enqueues a callback to the onStart Event
     *
     * @param callable $callback
     */
    public function onStart(callable $callback)
    {
        $this->events['start'][] = $callback;
    }

    /**
     * enqueues a callback to the onShutdown event
     *
     * @param callable $callback
     */
    public function onShutdown(callable $callback)
    {
        $this->events['shutdown'][] = $callback;
    }

    /**
     * enqueues a callback to the onWorkerStart event
     *
     * @param callable $callback
     */
    public function onWorkerStart(callable $callback)
    {
        $this->events['workerstart'][] = $callback;
    }

    /**
     * enqueues a callback to the onWorkerStop event
     *
     * @param callable $callback
     */
    public function onWorkerStop(callable $callback)
    {
        $this->events['workerstop'][] = $callback;
    }

    /**
     * enqueues a callback to the onTimer event
     *
     * @param callable $callback
     */
    public function onTimer(callable $callback)
    {
        $this->events['timer'][] = $callback;
    }

    /**
     * enqueues a callback to the onClose event
     *
     * @param callable $callback
     */
    public function onClose(callable $callback)
    {
        $this->events['close'][] = $callback;
    }

    /**
     * enqueues a callback to the onTask event
     *
     * @param callable $callback
     */
    public function onTask(callable $callback)
    {
        $this->events['task'][] = $callback;
    }

    /**
     * enqueues a callback to the onFinish event
     *
     * @param callable $callback
     */
    public function onFinish(callable $callback)
    {
        $this->events['finish'][] = $callback;
    }

    /**
     * enqueues a callback to the onPipeMessage event
     *
     * @param callable $callback
     */
    public function onPipeMessage(callable $callback)
    {
        $this->events['pipemessage'][] = $callback;
    }

    /**
     * enqueues a callback to the onWorkerError event
     *
     * @param callable $callback
     */
    public function onWorkerError(callable $callback)
    {
        $this->events['workererror'][] = $callback;
    }

    /**
     * enqueues a callback to the onManagerStart event
     *
     * @param callable $callback
     */
    public function onManagerStart(callable $callback)
    {
        $this->events['managerstart'][] = $callback;
    }

    /**
     * enqueues a callback to the onManagerStop event
     *
     * @param callable $callback
     */
    public function onManagerStop(callable $callback)
    {
        $this->events['managerstop'][] = $callback;
    }

    /**
     * acknowledges events from the provided object.
     *
     * @param $object
     */
    public function acknowledge($object)
    {
        foreach ( self::$eventNames as $current ) {
            if ( method_exists($object, $current) ) {
                $this->{$current}(
                    [$object, $current]
                );
            }
        }
    }

    /**
     * creates a generator of events as a data stream
     *
     * Event Name => callback
     *
     * @return \Generator
     */
    public function streamEvents(): \Generator
    {
        foreach ( $this->events as $current => $pool ) {
            foreach ( $pool as $callback ) {
                yield $current => $callback;
            }
        }
    }
}