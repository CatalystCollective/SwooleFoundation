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
use Catalyst\Swoole\Servants\FactoryServant;

class ServerFactory implements ServerFactoryInterface, ServantAwareInterface
{
    protected $servant;

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
        $parameter = $this->servant->reflect($closure)->getParameters();
        $events = new HttpServerEvents();
        $settings = new ServerConfiguration();

        $inject = [];
        foreach ( $parameter as $current ) {
            if ( $current->getClass() && $current->getClass()->getName() === HttpServerEvents::class ) {
                $inject[$current->getPosition()] = $events;
                continue;
            }

            if ( $current->getClass() && $current->getClass()->getName() === ServerConfiguration::class ) {
                $inject[$current->getPosition()] = $settings;
                continue;
            }

            $inject[$current->getPosition()] = $this->servant->resolveFromReflection($current);
        }

        $closure(... $inject);

        $bindings = $settings->getBindings();
        $majorBinding = array_shift($bindings);

        $server = new \swoole_http_server(
            $majorBinding['host'],
            $majorBinding['port'],
            $settings->getMode(),
            $majorBinding['socket']
        );

        $server->set($settings->getSettings());

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
        // TODO: Implement webSocketServer() method.
    }

    /**
     * creates a server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_server
     */
    public function server(\Closure $closure): \swoole_server
    {
        // TODO: Implement server() method.
    }

}