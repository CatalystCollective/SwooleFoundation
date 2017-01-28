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


/**
 * Interface ServerFactoryInterface
 *
 * @package Catalyst\Swoole
 */
interface ServerFactoryInterface
{
    /**
     * swoole constant import
     */
    const SOCK_TCP = \SWOOLE_SOCK_TCP;

    /**
     * swoole constant import
     */
    const SOCK_TCP6 = \SWOOLE_SOCK_TCP6;

    /**
     * swoole constant import
     */
    const SOCK_UDP = \SWOOLE_SOCK_UDP;

    /**
     * swoole constant import
     */
    const SOCK_UDP6 = \SWOOLE_SOCK_UDP6;

    /**
     * swoole constant import
     */
    const SOCK_UNIX_DGRAM = \SWOOLE_SOCK_UNIX_DGRAM;

    /**
     * swoole constant import
     */
    const SOCK_UNIX_STREAM = \SWOOLE_SOCK_UNIX_STREAM;

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
    public function create(string $host, int $port, int $mode = \SWOOLE_PROCESS, int $sock_type = \SWOOLE_SOCK_TCP, array $options = []): \swoole_server;

    /**
     * creates a http server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_http_server
     */
    public function httpServer(\Closure $closure): \swoole_http_server;

    /**
     * creates a websocket server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_websocket_server
     */
    public function webSocketServer(\Closure $closure): \swoole_websocket_server;

    /**
     * creates a server orchestrated by the provided closure.
     *
     * @param \Closure $closure
     * @return \swoole_server
     */
    public function server(\Closure $closure): \swoole_server;
}