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


/**
 * Class HttpServerConfiguration
 *
 * @package Catalyst\Swoole\Entities
 */
class ServerConfiguration
{
    /**
     * setting type: string - ctype compatible string
     */
    const STRING_TYPE = 'string';

    /**
     * setting type: integer - ctype compatible string
     */
    const INTEGER_TYPE = 'integer';

    /**
     * setting type: boolean - ctype compatible string
     */
    const BOOLEAN_TYPE = 'boolean';

    /**
     * swoole mode
     *
     * @var int
     */
    protected $mode = \SWOOLE_BASE;

    /**
     * array of bindings
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * array of validated settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected static $settingTypes = [
        'chroot' => ServerConfiguration::STRING_TYPE,
        'user' => ServerConfiguration::INTEGER_TYPE,
        'group' => ServerConfiguration::STRING_TYPE,
        'daemonize' => ServerConfiguration::BOOLEAN_TYPE,
        'backlog' => ServerConfiguration::INTEGER_TYPE,
        'reactor_num' => ServerConfiguration::INTEGER_TYPE,
        'worker_num' => ServerConfiguration::INTEGER_TYPE,
        'discard_timeout_request' => ServerConfiguration::BOOLEAN_TYPE,
        'enable_unsafe_event' => ServerConfiguration::BOOLEAN_TYPE,
        'task_worker_num' => ServerConfiguration::INTEGER_TYPE,
        'task_worker_max' => ServerConfiguration::INTEGER_TYPE,
        'task_ipc_mode' => ServerConfiguration::INTEGER_TYPE,
        'task_tmpdir' => ServerConfiguration::STRING_TYPE,
        'max_connection' => ServerConfiguration::INTEGER_TYPE,
        'max_request' => ServerConfiguration::INTEGER_TYPE,
        'task_max_request' => ServerConfiguration::INTEGER_TYPE,
        'open_cpu_affinity' => ServerConfiguration::INTEGER_TYPE,
        'cpu_affinity_ignore' => true,
        'open_tcp_nodelay' => ServerConfiguration::BOOLEAN_TYPE,
        'tcp_defer_accept' => ServerConfiguration::INTEGER_TYPE,
        'enable_port_reuse' => ServerConfiguration::BOOLEAN_TYPE,
        'open_tcp_keepalive' => ServerConfiguration::BOOLEAN_TYPE,
        'open_eof_split' => ServerConfiguration::BOOLEAN_TYPE,
        'package_eof' => ServerConfiguration::STRING_TYPE,
        'open_http_protocol' => ServerConfiguration::BOOLEAN_TYPE,
        'http_parse_post' => ServerConfiguration::BOOLEAN_TYPE,
        'open_mqtt_protocol' => ServerConfiguration::BOOLEAN_TYPE,
        'tcp_keepidle' => ServerConfiguration::INTEGER_TYPE,
        'tcp_keepinterval' => ServerConfiguration::INTEGER_TYPE,
        'tcp_keepcount' => ServerConfiguration::INTEGER_TYPE,
        'dispatch_mode' => ServerConfiguration::INTEGER_TYPE,
        'open_dispatch_key' => ServerConfiguration::INTEGER_TYPE,
        'dispatch_key_type' => ServerConfiguration::STRING_TYPE,
        'dispatch_key_offset' => ServerConfiguration::INTEGER_TYPE,
        'log_file' => ServerConfiguration::STRING_TYPE,
        'heartbeat_check_interval' => ServerConfiguration::INTEGER_TYPE,
        'heartbeat_idle_time' => ServerConfiguration::INTEGER_TYPE,
        'heartbeat_ping' => ServerConfiguration::STRING_TYPE,
        'heartbeat_pong' => ServerConfiguration::STRING_TYPE,
        'open_length_check' => ServerConfiguration::BOOLEAN_TYPE,
        'package_length_type' => ServerConfiguration::STRING_TYPE,
        'package_length_offset' => ServerConfiguration::INTEGER_TYPE,
        'package_body_start' => ServerConfiguration::INTEGER_TYPE,
        'buffer_input_size' => ServerConfiguration::INTEGER_TYPE,
        'buffer_output_size' => ServerConfiguration::INTEGER_TYPE,
        'pipe_buffer_size' => ServerConfiguration::INTEGER_TYPE,
        'ssl_cert_file' => ServerConfiguration::STRING_TYPE,
        'ssl_key_file' => ServerConfiguration::STRING_TYPE,
        'ssl_method' => ServerConfiguration::INTEGER_TYPE
    ];

    /**
     * sets a validated value to a allowed key.
     *
     * @param string $key
     * @param $value
     * @return ServerConfiguration
     */
    public function set(string $key, $value)
    {
        if ( ! array_key_exists($key, self::$settingTypes) ) {
            throw new \RuntimeException('Unknown setting: '.$key);
        }

        if ( gettype($value) !== self::$settingTypes[$key] ) {
            throw new \LogicException('Type mismatch for setting `'.$key.'`, expecting: '.self::$settingTypes[$key]);
        }

        $this->settings[$key] = $value;

        return $this;
    }

    /**
     * assigns an validated array of settings.
     *
     * @param array $settings
     * @return ServerConfiguration
     */
    public function assign(array $settings)
    {
        foreach ( $settings as $key => $value ) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * adds a resource binding.
     *
     * @param string $resource
     * @param int|null $port
     * @param int|null $socket
     * @return ServerConfiguration
     */
    public function withBinding(string $resource, int $port = null, int $socket = null)
    {
        if ( 0 < count($this->bindings) ) {
            $port = $port ?? $this->bindings[0]['port'];
            $socket = $socket ?? $this->bindings[0]['socket'];
        }
        else {
            $port = $port ?? 80;
            $socket = $socket ?? filter_var($resource, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? \SWOOLE_SOCK_TCP6 : \SWOOLE_SOCK_TCP;
        }

        $this->bindings[] = compact('resource', 'port', 'socket');

        return $this;
    }

    /**
     * adds a unix socket binding.
     *
     * @param string $path
     * @param bool $defaultUnixStream
     * @return ServerConfiguration
     */
    public function withUnixSocket(string $path, bool $defaultUnixStream = true)
    {
        $this->withBinding($path, 0, $defaultUnixStream ? \SWOOLE_SOCK_UNIX_STREAM : \SWOOLE_SOCK_UNIX_DGRAM);

        return $this;
    }

    /**
     * sets the swoole mode to "process"
     *
     * @return $this
     */
    public function asProcess()
    {
        $this->mode = \SWOOLE_PROCESS;

        return $this;
    }

    /**
     * sets the swoole mode to "thread"
     *
     * @return $this
     */
    public function asThread()
    {
        $this->mode = \SWOOLE_THREAD;

        return $this;
    }

    /**
     * sets the swoole mode to "base"
     *
     * @return $this
     */
    public function asCommon()
    {
        $this->mode = \SWOOLE_BASE;

        return $this;
    }

    /**
     * getter for the bindings array.
     *
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * getter for the settings array.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * getter for the swoole mode.
     *
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }
}