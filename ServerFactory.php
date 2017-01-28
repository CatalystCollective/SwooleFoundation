<?php
/**
 * This file is part of the Catalyst Swoole Foundation.
 *
 * (c)2017 Matthias Kaschubowski and the Catalyst Collective
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Catalyst\Swoole;


use Catalyst\Servant\Exceptions\ServantException;
use Catalyst\Servant\ServantAwareInterface;
use Catalyst\Servant\ServantInterface;
use Catalyst\Swoole\Entities\HttpServerEvents;
use Catalyst\Swoole\Entities\ServerConfiguration;
use Catalyst\Swoole\Entities\ServerEvents;
use Catalyst\Swoole\Entities\WebsocketServerEvents;
use Catalyst\Swoole\Servants\FactoryServant;

/**
 * Class ServerFactory
 *
 * @package Catalyst\Swoole
 */
class ServerFactory implements ServerFactoryInterface, ServantAwareInterface
{
    /**
     * @var FactoryServant
     */
    protected $servant;

    /**
     * ServerFactory constructor.
     */
    public function __construct()
    {
        $this->servant = new FactoryServant();
    }

    /**
     * sets or pulls the current servant interface.
     *
     * @param ServantInterface|null $servant
     * @throws ServantException when not servant instance is given and the command must pull one.
     * @return ServantInterface
     */
    public function servant(ServantInterface $servant = null): ServantInterface
    {
        if ( $servant instanceof ServantInterface ) {
            throw new ServantException('The first servant can not be replaced in this implementation');
        }

        return $this->servant;
    }

    /**
     * creates a server instance by the provided options array.
     *
     * @param string $host
     * @param int $port
     * @param int $mode
     * @param int $sock_type
     * @param array $options
     * @return \swoole_server
     */
    public function create(string $host, int $port, int $mode = \SWOOLE_BASE, int $sock_type = \SWOOLE_SOCK_TCP6, array $options = []): \swoole_server
    {
        $config = new ServerConfiguration();
        $config->assign($options);

        $server = new \swoole_server($host, $port, $mode, $sock_type);
        $server->set($config->getSettings());

        return $server;
    }

    /**
     * creates a http server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_http_server
     */
    public function httpServer(\Closure $closure): \swoole_http_server
    {
        $events = new HttpServerEvents();
        $settings = new ServerConfiguration();

        $closure(... $this->marshalDependencies($closure, $events, $settings));

        $bindings = $settings->getBindings();

        if ( empty($bindings) ) {
            throw new \LogicException('Server configurations must serve at least one binding');
        }

        $majorBinding = array_shift($bindings);

        $server = new \swoole_http_server(
            $majorBinding['host'],
            $majorBinding['port'],
            $settings->getMode(),
            $majorBinding['socket']
        );

        foreach ( $bindings as $current ) {
            $server->addlistener($current['host'], $current['port'], $current['socket']);
        }

        $this->injectEventsAndSettingsInto($server, $events, $settings);

        return $server;
    }

    /**
     * creates a websocket server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_websocket_server
     */
    public function webSocketServer(\Closure $closure): \swoole_websocket_server
    {
        $events = new WebsocketServerEvents();
        $settings = new ServerConfiguration();

        $closure(... $this->marshalDependencies($closure, $events, $settings));

        $bindings = $settings->getBindings();

        if ( empty($bindings) ) {
            throw new \LogicException('Server configurations must serve at least one binding');
        }

        $majorBinding = array_shift($bindings);

        $server = new \swoole_websocket_server(
            $majorBinding['host'],
            $majorBinding['port'],
            $settings->getMode(),
            $majorBinding['socket']
        );

        foreach ( $bindings as $current ) {
            $server->addlistener($current['host'], $current['port'], $current['socket']);
        }

        $this->injectEventsAndSettingsInto($server, $events, $settings);

        return $server;
    }

    /**
     * creates a server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_server
     */
    public function server(\Closure $closure): \swoole_server
    {
        $events = new ServerEvents();
        $settings = new ServerConfiguration();

        $closure(... $this->marshalDependencies($closure, $events, $settings));

        $bindings = $settings->getBindings();

        if ( empty($bindings) ) {
            throw new \LogicException('Server configurations must serve at least one binding');
        }

        $majorBinding = array_shift($bindings);

        $server = new \swoole_server(
            $majorBinding['host'],
            $majorBinding['port'],
            $settings->getMode(),
            $majorBinding['socket']
        );

        foreach ( $bindings as $current ) {
            $server->addlistener($current['host'], $current['port'], $current['socket']);
        }

        $this->injectEventsAndSettingsInto($server, $events, $settings);

        return $server;
    }

    /**
     * marshals the dependencies for a closure.
     *
     * @param \Closure $closure
     * @param EventsEntityInterface $events
     * @param ServerConfiguration $settings
     * @return \Generator
     */
    protected function marshalDependencies(\Closure $closure, EventsEntityInterface $events, ServerConfiguration $settings)
    {
        $parameter = $this->servant->reflect($closure)->getParameters();

        foreach ( $parameter as $current ) {
            if ( $current->getClass() && $current->getClass()->getName() === get_class($events) ) {
                yield $events;
                continue;
            }

            if ( $current->getClass() && $current->getClass()->getName() === ServerConfiguration::class ) {
                yield $settings;
                continue;
            }

            yield $this->servant->resolveFromReflection($current);
        }
    }

    /**
     * injector for events and settings to a server instance.
     *
     * @param \swoole_server $server
     * @param EventsEntityInterface $events
     * @param ServerConfiguration $settings
     * @return void
     */
    protected function injectEventsAndSettingsInto(\swoole_server $server, EventsEntityInterface $events, ServerConfiguration $settings)
    {
        $server->set($settings->getSettings());

        foreach ( $events->streamEvents() as $name => $callback ) {
            $server->on($name, $callback);
        }
    }
}