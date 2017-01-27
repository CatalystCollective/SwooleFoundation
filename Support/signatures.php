<?php
/**
 * This file is part of the Catalyst Swoole Foundation.
 *
 * (c)2017 Matthias Kaschubowski and the Catalyst Collective
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

const SWOOLE_BASE = 4;
const SWOOLE_THREAD = 2;
const SWOOLE_PROCESS = 3;
const SWOOLE_IPC_UNSOCK = 1;
const SWOOLE_IPC_MSGQUEUE = 2;
const SWOOLE_IPC_CHANNEL = 3;
const SWOOLE_SOCK_TCP = 1;
const SWOOLE_SOCK_TCP6 = 3;
const SWOOLE_SOCK_UDP = 2;
const SWOOLE_SOCK_UDP6 = 4;
const SWOOLE_SOCK_UNIX_DGRAM = 5;
const SWOOLE_SOCK_UNIX_STREAM = 6;
const SWOOLE_TCP = 1;
const SWOOLE_TCP6 = 3;
const SWOOLE_UDP = 2;
const SWOOLE_UDP6 = 4;
const SWOOLE_UNIX_DGRAM = 5;
const SWOOLE_UNIX_STREAM = 6;
const SWOOLE_SOCK_SYNC = 0;
const SWOOLE_SOCK_ASYNC = 1;
const SWOOLE_SYNC = 2048;
const SWOOLE_ASYNC = 1024;
const SWOOLE_KEEP = 4096;
const SWOOLE_EVENT_READ = 512;
const SWOOLE_EVENT_WRITE = 1024;
const SWOOLE_AIO_BASE = 0;
const SWOOLE_AIO_LINUX = 1;
const SWOOLE_FILELOCK = 2;
const SWOOLE_MUTEX = 3;
const SWOOLE_SEM = 4;
const SWOOLE_RWLOCK = 1;
const WEBSOCKET_OPCODE_TEXT = 1;
const WEBSOCKET_OPCODE_BINARY = 2;
const WEBSOCKET_STATUS_CONNECTION = 1;
const WEBSOCKET_STATUS_HANDSHAKE = 2;
const WEBSOCKET_STATUS_FRAME = 3;
const WEBSOCKET_STATUS_ACTIVE = 3;


/**
 * Atomic operation for swoole server.
 *
 * It used shared memory and can operate beetween different process.
 * gcc based CPU atomic instructions provided, without locking.
 * Must be created before swoole_server->start in order to be used on the worker process
 *
 * @package Swoole
 */
class swoole_atomic
{
    /**
     * @param int $init_value
     */
    public function __construct($init_value) {}

    /**
     * Increment the number
     *
     * @param $add_value
     * @return int
     */
    public function add($add_value) {}

    /**
     * Decrement the number
     *
     * @param $sub_value
     * @return int
     */
    public function sub($sub_value) {}

    /**
     * Get the current value
     *
     * @return int
     */
    public function get() {}

    /**
     * Set the current value
     *
     * @param $value
     */
    public function set($value) {}

    /**
     * If the current value is equal to parameter 1
     *
     * @param int $cmp_value
     * @param int $set_value
     */
    public function cmpset($cmp_value, $set_value) {}
}

/**
 * Memory Operations for binary & string
 *
 * @package Swoole
 */
class swoole_buffer
{
    /**
     * @param int $size
     */
    function __construct($size = 128) {}

    /**
     * Append data at the end of the string buffer
     *
     * @param string $data
     * @return int
     */
    function  append($data) {}

    /**
     * Remove the contents from the buffer.
     *
     * The memory is not immediately released, you need to destruct the object in order to really free up memory
     *
     * @param int $offset the offset, can be negative
     * @param int $length the length of the data, the default is from $offset to the end of entire buffer
     * @param bool $remove is data will be removed from the head of the buffer. Only valid with $offset = 0
     */
    function  substr($offset, $length = -1, $remove = false) {}

    /**
     * Cleanup data
     *
     * The buffer will be reset. the object can be reused to handle new requests.
     * Swoole_buffer pointer-based operations to achieve clear, and will not write memory
     */
    function  clear() {}

    /**
     * Buffer zone expansion
     *
     * @param int $new_size specify new buffer size, must be greater than the current size
     */
    function  expand($new_size) {}

    /**
     * Write data to an arbitrary memory location.
     *
     * This function write directly to memory. Use with caution, as this may destruct the existing data.
     * This method does not automatically expand the size.
     *
     * @param int $offset
     * @param string $data written data, must not exceed the maximum size of the cache.
     */
    function  write($offset, $data) {}
}

/**
 * Http client
 *
 * @package Swoole
 */
class swoole_client
{
    /**
     * Last error code
     *
     * @var int
     */
    public $errCode;

    /**
     * Socket file descriptor
     *
     * Example
     * ```php
     * $sock = fopen("php://fd/".$swoole_client->sock);
     * ```
     *
     * The swoole_client is a stream socket. You can call fread / fwrite / fclose functions.
     * For swoole_server, the $fd cannot use this method to convert as $fd is just a number, $fd file descriptor is part of the main process
     * $swoole_client->sock can be converted to int as the key array.
     *
     * @var int
     */
    public $sock;

    /**
     * @param int $sock_type specified socket type and supports TCP / UDP, TCP6 / UDP64
     * @param int $sync_type SWOOLE_SOCK_SYNC/SWOOLE_SOCK_ASYNC synchronous / asynchronous
     */
    public function __construct($sock_type, $sync_type = SWOOLE_SOCK_SYNC) {}

    /**
     * Connect to the remote server
     *
     * Before send / recv swoole_client_select must be used to detect if the connection is open
     *
     * @param string $host is the address of the remote server, v1.6.10+ Swoole will automatically fill the domain DNS query
     * @param int $port is the remote server port
     * @param float $timeout is network IO timeout, the unit is s, support for floating point. The default is 0.1s, ie 100ms
     * @param int $flag parameter when UDP type indicates whether udp_connect enabled. Setting this option will bind $host and $port, the UDP will discard non-designated host / port packet.
     * @return bool
     */
    public function connect($host, $port, $timeout = 0.1, $flag = 0) {}

    /**
     * Sending data to a remote server
     *
     * Parameter is a string, support for binary data.
     * On success, returned data length sent
     * On failure, return false and sets $swoole_client->errCode
     *
     * @param string $data
     * @return bool|int
     */
    public function send($data) {}

    /**
     * Sent data to any ip:port, only support UDP / UDP6
     *
     * @param $ip
     * @param $port
     * @param $data
     */
    function sendto($ip, $port, $data) {}

    /**
     * Receive data from the server
     *
     * If you set the $waitall you must set the correct $size, otherwise they will have to wait until the data length reaches $size is received
     * If you set the wrong $size, can cause recv timeout & returns false
     * Successful call returns the resulting string, failed to return false, and sets $swoole_client->errCode property
     *
     * @param int $size the maximum length of the received data
     * @param bool $waitall whether to wait for all the data $size
     * @return string|bool
     */
    public function recv($size = 65535, $waitall = false) {}

    /**
     * Close remote connection
     *
     * Automaticaly call on destruct
     *
     * @return bool
     */
    public function close() {}

    /**
     * Register asynchronous event callback function
     *
     * @param $event_name (connect, receive, error, close)
     * @param callable $callback_function
     * @return bool
     */
    public function on($event_name, $callback_function) {}

    /**
     * Is connected to the server
     *
     * @return bool
     */
    public function isConnected() {}

    /**
     * Get client socket of host:port information
     *
     * @return bool|array
     */
    public function getsockname() {}

    /**
     * Get the peername of this connection, only for UDP / UDP6
     *
     * UDP sends data to the server, response may be sent by another server
     *
     * @return bool|array
     */
    public function getpeername() {}
}

/**
 * Http request object
 *
 * @package Swoole
 */
class swoole_http_request
{
    /**
     * QueryString
     *
     * @var array
     */
    public $get;

    /**
     * PostData
     *
     * @var array
     */
    public $post;

    /**
     * Headers
     *
     * @var array
     */
    public $header;

    /**
     * Server variable
     *
     * @var array
     */
    public $server;

    /**
     * Cookie
     *
     * @var array
     */
    public $cookie;

    /**
     * Files uploaded
     *
     * @var array
     */
    public $files;

    /**
     * File descriptor
     *
     * @var int
     */
    public $fd;

    /**
     * Get original form POST data (the non urlencoded-form)
     *
     * @return string
     */
    public function rawContent() {}
}

/**
 * Http response object
 *
 * @package Swoole
 */
class swoole_http_response
{
    /**
     * End Http response, send HTML content
     *
     * @param string $html
     */
    public function end($html = '') {}

    /**
     * Send Http chunk of data to the browser
     *
     * @param string $html
     */
    public function write($html) {}

    /**
     * Add a header
     *
     * @param string $key
     * @param string $value
     */
    public function header($key, $value) {}

    /**
     * Set a cookie
     *
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     */
    public function cookie($key, $value, $expire = 0, $path='/', $domain='', $secure=false, $httponly=false) {}

    /**
     * Set Http Status Code, such as 404, 501, 200
     *
     * @param int $code
     */
    public function status($code) {}

    /**
     * Set http compression format
     *
     * @param int $level
     */
    public function gzip($level = 1) {}
}

/**
 * Built-in Web server
 *
 * @package Swoole
 */
class swoole_http_server extends swoole_server
{
    /**
     * Enable fill of the $GLOBALS variables with GET, POST, COOKIE data
     *
     * @param int $flag HTTP_GLOBAL_ALL, HTTP_GLOBAL_GET, HTTP_GLOBAL_POST, HTTP_GLOBAL_COOKIE
     * @param int $request_flag
     */
    function setGlobal($flag, $request_flag = 0) {}
}

/**
 * Locking operation for swoole server.
 *
 * Each type of lock doesn't support all methods (such as read-write locks), file locks support $lock->lock_read().
 *
 * Other types of locks than file lock must be created within the parent process in order to handle locking from fork childs.
 *
 * @package Swoole
 */
class swoole_lock
{
    /**
     * @param int $type the type of lock (SWOOLE_FILELOCK, SWOOLE_MUTEX, SWOOLE_RWLOCK, SWOOLE_SPINLOCK, SWOOLE_SEM)
     * @param string $lockfile when the type is SWOOLE_FILELOCK, you must specify the path of the file lock
     */
    public function __construct($type, $lockfile = NULL) {}

    /**
     * Read Write Lock operation
     *
     * If there is another process that hold the lock, the call of this function will block execution process
     */
    public function lock() {}

    /**
     * Non-blocking lock
     *
     * The same than lock but release immediatly if there is a lock
     * Unavailable with SWOOlE_SEM semaphore
     *
     * @return bool if the lock is free
     */
    public function trylock() {}

    /**
     * Release the lock
     */
    public function unlock() {}

    /**
     * Read locking only
     *
     * lock_read method is only available in read-write lock (SWOOLE_RWLOCK) and file locks (SWOOLE_FILELOCK)
     * If there is another process that hold the read_lock, the call of this function will block execution process
     * Other processes can still get a read lock while there is no an exclusive lock with $lock->lock() or $lock->trylock ().
     */
    public function lock_read() {}

    /**
     * Non-blocking lock
     * the same than lock_read but release immediatly if there is a lock
     *
     * @return bool if the lock is free
     */
    public function trylock_read() {}
}

/**
 * Swoole Process Management
 *
 * Can facilitate communication IPC communication, the child process and the main process
 * Supports standard input and output redirection, the child process echo will be sent to the pipeline, instead of the output screen
 *
 * @package Swoole
 */
class swoole_process
{
    /**
     * PID process
     *
     * @var int
     */
    public $pid;

    /**
     * Pipeline
     *
     * @var int
     */
    public $pipe;

    /**
     * @param mixed $callback callback function subprocess
     * @param bool $redirect_stdin_stdout whether to redirect the standard input and output
     * @param bool $create_pipe whether to create a pipeline
     */
    public function __construct($callback, $redirect_stdin_stdout = false, $create_pipe = true) {}

    /**
     * Write data to the pipe
     *
     * @param string $data
     * @return int
     */
    public function write($data) {}

    /**
     * Read data from the pipe
     *
     * @param int $buffer_len Maximum read length
     * @return string
     */
    public function read($buffer_len = 8192) {}

    /**
     * Exit sub-process
     *
     * @param int $code
     * @return int
     */
    public function exit($code = 0) {}

    /**
     * Execute another process
     *
     * @param string $execute_file
     * @param array $params
     * @return bool
     */
    public function exec($execute_file, $params){ }

    /**
     * Wait for the child process exits
     *
     * on success, return return PID and exit status code array('code' => 0, 'pid' => 15001)
     * on failure, return false
     *
     * @return array|bool
     */
    public static function wait() {}

    /**
     * Deamonize process
     *
     * @param bool $nochdir
     * @param bool $noclose
     */
    public static function daemon($nochdir = false, $noclose = false) {}

    /**
     * Create a message queue
     *
     * @param int $msgkey Message Queuing Key
     * @param int $mode mode
     */
    public function useQueue($msgkey = -1, $mode = 2) {}

    /**
     * Push data to the message queue
     *
     * @param string $data
     */
    public function push($data) {}

    /**
     * Pop data from the message queue
     *
     * @param int $maxsize
     * @return string
     */
    public function pop($maxsize = 8192) {}

    /**
     * Send a signal to a process
     *
     * @param int $pid
     * @param int $sig
     */
    public static function kill($pid, $sig = SIGTERM) {}

    /**
     * Register signal handler function
     *
     * require swoole 1.7.9+
     *
     * @param int $signo
     * @param mixed $callback
     */
    public static function signal($signo, $callback) {}

    /**
     * Start the process
     *
     * @return int
     */
    public function start() {}

    /**
     * Rename the process name
     *
     * @param string $process_name
     */
    public function name($process_name) {}
}

/**
 * TCP / UDP Server
 *
 * @package Swoole
 */
class swoole_server
{
    /**
     * swoole_server::set() function to set setting
     *
     * @var array
     */
    public $setting = array();

    /**
     * Main process PID
     *
     * @var int
     */
    public $master_pid;

    /**
     * Server management process PID
     *
     * Only available after onStart / onWorkerStart
     *
     * @var int
     */
    public $manager_pid;

    /**
     * The current number Worker process
     *
     * - Worker process ID range is [0, $serv->setting['worker_num'])
     * - Task process ID range is [$serv->setting['worker_num'], $serv->setting['worker_num'] + $serv->setting['task_worker_num'])
     *
     * On restart, workers process id is unchanged
     *
     * @var int
     */
    public $worker_id;

    /**
     * Current Worker process ID, 0 - ($serv->setting[worker_num]-1)
     *
     * @var int
     */
    public $worker_pid;

    /**
     * Id a Task worker process
     *
     * true indicates that the current process is a Task work process
     * false indicates that the current process is a Worker Process
     *
     * @var bool
     */
    public $taskworker;

    /**
     * TCP connection iterator for all current server connections, same than swoole_server->connnection_list(), but more friendly.
     *
     * Rely pcre library, if not installed, you cannot use this function
     *
     * ```php
     * foreach($server->connections as $fd)
     * {
     *   $server->send($fd, "hello");
     * }
     *
     * echo "Current server connections : ".count($server->connections). "\n";
     * ```
     *
     * @var array
     */
    public $connections;

    /**
     * Swoole_server Constructor
     *
     * @param string $host
     * @param int $port
     * @param int $mode SWOOLE_BASE, SWOOLE_THREAD, SWOOLE_PROCESS, SWOOLE_PACKET
     * @param int $sock_type SWOOLE_SOCK_TCP, SWOOLE_SOCK_TCP6, SWOOLE_SOCK_UDP, SWOOLE_SOCK_UDP6, SWOOLE_SOCK_UNIX_DGRAM, SWOOLE_SOCK_UNIX_STREAM, If you want use ssl just or (|) your current socket type with SWOOLE_SSL
     */
    function __construct($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP) {}

    /**
     * Register event callback function
     *
     * swoole_server->on & swoole_http_server->on are the same except swoole_http_server :
     * - not accepting onConnect/onReceive callback
     * - accept events onRequest
     *
     * **Event List:**
     * - onStart
     * - onShutdown
     * - onWorkerStart
     * - onWorkerStop
     * - onTimer
     * - onConnect
     * - onReceive
     * - onClose
     * - onTask
     * - onFinish
     * - onPipeMessage
     * - onWorkerError
     * - onManagerStart
     * - onManagerStop
     *
     * ```php
     * $http_server->on('request', function(swoole_http_request $request, swoole_http_response $response) {
     *   $response->end("<h1>hello swoole</h1>");
     * })
     * ```
     *
     *
     * The callback function must have 2 parameters
     *
     * - $request，Http Request object that contains the header / get / post / cookie and other related information
     * - $response，Http Response object that supports cookie / header / status, etc...
     *
     *
     * !! if $response/$request object passed to other functions, do not add a reference symbol &
     *
     * @param string $event
     * @param mixed $callback
     */
    public function on($event, $callback) {}

    /**
     * Set run-time parameter is used to set various parameters swoole_server runtime.
     *
     * After the server starts, use $serv->setting to have this settings.
     *
     * ```php
     * $setting["chroot"]; // string : chroot
     * $setting["user"]; // int : unix user
     * $setting["group"]; // string : unix group
     * $setting["daemonize"]; // bool : daemonize
     * $setting["backlog"]; // int : backlog
     * $setting["reactor_num"]; // int : reactor thread num
     * $setting["worker_num"]; // int : worker num
     * $setting["discard_timeout_request"]; // bool : ??
     * $setting["enable_unsafe_event"]; // bool : ??
     * $setting["task_worker_num"]; // int : task worker num
     * $setting["task_worker_max"]; // int : maximum task worker
     * $setting["task_ipc_mode"]; // int : 1, 2, 3
     * $setting["task_tmpdir"]; // string : Temporary file directory for task_worker
     * $setting["max_connection"]; // int : max_connection
     * $setting["max_request"]; // int : max_request
     * $setting["task_max_request"]; // int : task_max_request
     * $setting["open_cpu_affinity"]; // bool: cpu affinity
     * $setting["cpu_affinity_ignore"]; // ?? : cpu affinity set
     * $setting["open_tcp_nodelay"]; // bool : tcp_nodelay
     * $setting["tcp_defer_accept"]; // int : tcp_defer_accept
     * $setting["enable_port_reuse"]; // bool : port reuse
     * $setting["open_tcp_keepalive"]; // bool : tcp_keepalive
     * $setting["open_eof_split"]; // bool : buffer: split package with eof
     * $setting["package_eof"]; // string : package eof
     * $setting["open_http_protocol"]; // bool : buffer: http_protocol
     * $setting["http_parse_post"]; // bool : parse x-www-form-urlencoded form data
     * $setting["open_mqtt_protocol"]; // bool : buffer: mqtt protocol
     * $setting["tcp_keepidle"]; // int : tcp_keepidle
     * $setting["tcp_keepinterval"]; // int : tcp_keepinterval
     * $setting["tcp_keepcount"]; // int : tcp_keepcount
     * $setting["dispatch_mode"]; // int : dispatch_mode
     * $setting["open_dispatch_key"]; // int : open_dispatch_key
     * $setting["dispatch_key_type"]; // string : dispatch_key_type see pack(). Link: http://php.net/pack
     * $setting["dispatch_key_offset"]; // int : dispatch_key_offset
     * $setting["log_file"]; // string : log_file
     * $setting["heartbeat_check_interval"]; // int : heartbeat_check_interval
     * $setting["heartbeat_idle_time"]; // int : heartbeat idle time, heartbeat_idle_time must be greater than heartbeat_check_interval
     * $setting["heartbeat_ping"]; // string : heartbeat_ping, max 8 chars
     * $setting["heartbeat_pong"]; // string : heartbeat_pong, max 8 chars
     * $setting["open_length_check"]; // bool : open length check
     * $setting["package_length_type"]; // string : package length size
     * $setting["package_length_offset"]; // int : package length offset
     * $setting["package_body_start"]; // int : package body start
     * $setting["buffer_input_size"]; // int : buffer input size
     * $setting["buffer_output_size"]; // int : buffer output size
     * $setting["pipe_buffer_size"]; // int : set pipe memory buffer size
     * $setting["ssl_cert_file"]; // string : ssl_cert_file
     * $setting["ssl_key_file"]; // int : ssl_key_file
     * $setting["ssl_method"]; // int : ssl_method
     * ```
     *
     * @param array $setting
     */
    public function set(array $setting) {}

    /**
     * Start server, listens to all TCP / UDP port
     *
     * Creates worker_num + 2 processes after the successful launch. Main process + Manager process + worker_num
     *
     * @return bool
     */
    public function start() {}

    /**
     * Send the data to the client
     *
     * - $data，TCP protocol can not exceed 2M, UDP protocol can not exceed 64K
     * - on sucess, returns true
     * - on failure, returns false if the connection has been closed or failed to send
     *
     * TCP server
     *
     * - Send operations are atomic, if multiple processes simultaneously send data to the same connection, the data will not be corrupted
     * - If more than 2M of data to be transmitted, the data can be written to a temporary file, and then sent via sendfile Interface
     *
     * Swoole-1.6 or later does not need $from_id
     *
     * UDP server
     *
     * - Send operations will send data packets directly by the worker process, will not be forwarded through the primary process
     * - Use fd to save the client IP，from_id, from_fd & port
     * - If you send data immediately after onReceive, you don't need to pass $from_id
     * - if you send data to others client, you must be pass from_id
     * - Outside your netwoork, if you send more than 64K, data will be transmitted into several message, if one message is lost, it would cause the entire packet is discarded, we recommend sending packets below 1.5K
     *
     * @param int $fd
     * @param string $data
     * @param int $from_id
     * @return bool
     */
    public function send($fd, $data, $from_id = 0) {}

    /**
     * Send UDP packets to any ip:port
     *
     * Example
     * ```php
     * // send a hello world string to host 220.181.57.216 & port 9502
     * $server->sendto('220.181.57.216', 9502, "hello world");
     * // send UDP packets to IPv6 server
     * $server->sendto('2600:3c00::f03c:91ff:fe73:e98f', 9501, "hello world", true);
     * ```
     *
     * @param string $ip  as IPv4 string, such as 192.168.1.102. If the IP is not legitimate returns an error
     * @param int $port is the network port number 1-65535, if the port fails Send
     * @param string $data data content, which can be text or binary content
     * @param bool $ipv6 whether the IPv6 address, optional parameters, defaults to false
     * @return bool
     */
    public function sendto($ip, $port, $data, $ipv6 = false) {}

    /**
     * Turn off client connection
     *
     * !! Swoole-1.6 or later does not need $from_id
     *
     * Operation successful return true, otherwise returns false.
     *
     * When the server close the connection, the server will trigger onClose event.
     * Do not write clean-up logic on close method, OnClose callback should handle this.
     *
     * @param int $fd
     * @param int $from_id
     * @return bool
     */
    public function close($fd, $from_id = 0) {}

    /**
     * Deliver a synchronous task to task_worker pool.
     *
     * taskwait will block until the task is completed or $timeout reach
     * It return the result like the $serv->finish function. If this task timeout, where returns false.
     *
     * @param mixed $task_data
     * @param float $timeout
     * @param int $dst_worker_id give the task to a specific process id, default is random delivery
     * @return string
     */
    public function taskwait($task_data, $timeout = 0.5, $dst_worker_id = -1) {}

    /**
     * Deliver an asynchronous task to task_worker pool.
     *
     * This function returns immediately. worker process can continue to process a new request
     * The return value is an integer ($task_id), represents this task ID. onFinish event call will carry the $task_id.
     *
     * This function is used to run slow task asynchronously. For example in chat room server, you can use it to send a broadcast. When the task is completed, the task calls $serv->finish("finish") told the worker process this task has been completed. Of course swoole_server->finish is optional.
     *
     * - AsyncTask 1.6.4 version adds features not started by default task function, you need to manually set to start this function task_worker_num
     * - Number task_worker settings are set in swoole_server::set, example task_worker_num => 64 will have 64 slots for asynchronous tasks
     *
     * Precautions
     *
     * - Use swoole_server_task to set onTask and onFinish callback Server, otherwise swoole_server->start will fail
     * - The number of task operation must be lower than onTask processing speed, if not, task will fill buffer, resulting blocking the worker process, worker process will not receive any new request
     *
     * @param mixed $data data to send to task_worker process
     * @param int $dst_worker_id deliver the task to process id,
     * @return bool|int task_id on sucess,
     */
    public function task($data, $dst_worker_id = -1) {}


    /**
     * This function can send messages to any worker process or task process.
     *
     * You can call it in worker processes and management processes.
     * Process that receives the message will trigger onPipeMessage event
     *
     * !! You must register onPipeMessage event to be able to use this fonction
     * ```php
     * $serv = new swoole_server("0.0.0.0", 9501);
     * $serv->set(array(
     *     'worker_num' => 2,
     *     'task_worker_num' => 2,
     * ));
     * $serv->on('pipeMessage', function($serv, $src_worker_id, $data) {
     *     echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
     * });
     * $serv->on('task', function ($serv, $task_id, $from_id, $data){
     *     var_dump($task_id, $from_id, $data);
     * });
     * $serv->on('finish', function ($serv, $fd, $from_id){
     *
     * });
     * $serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
     *     if (trim($data) == 'task')
     *     {
     *         $serv->task("async task coming");
     *     }
     *     else
     *     {
     *         $worker_id = 1 - $serv->worker_id;
     *         $serv->sendMessage("hello task process", $worker_id);
     *     }
     * });
     *
     * $serv->start();
     * ```
     *
     * @param string $message the message sent
     * @param int $dst_worker_id to target a specific process Id (in range 0 ~ (worker_num + task_worker_num - 1))
     * @return bool
     */
    public function sendMessage($message, $dst_worker_id = -1) {}

    /**
     * This function is used to notify the worker process that task is completed from task process.
     *
     * This function can be passed to the worker process result data
     *
     * ```php
     * $serv->finish("response");
     * ```
     *
     * You must set the onFinish callback function to receive the data.
     * OnTask callback function is only available for task process
     *
     * swoole_server::finish is optional. If the worker process does not care about the results of task execution, you do not need to call this function
     * onTask callback function is equivalent to calling finish
     *
     * @param string $task_data
     */
    public function finish($task_data) {}

    /**
     * Heartbeat
     *
     * @todo
     *
     * @param bool $if_close_connection
     * @return array|bool
     */
    public function heartbeat($if_close_connection = true) {}

    /**
     * Get information about the connection
     *
     * connection_info for UDP server can be used but you need to pass $from_id
     * ```php
     * array (
     *   'from_id' => 0,
     *   'from_fd' => 12,
     *   'connect_time' => 1392895129,
     *   'last_time' => 1392895137,
     *   'from_port' => 9501,
     *   'remote_port' => 48918,
     *   'remote_ip' => '127.0.0.1',
     * );
     *
     * $udp_client = $serv->connection_info($fd, $from_id);
     * var_dump($udp_client);
     * ```
     *
     * - from_id Reactor thread id
     * - server_fd Server Socket file description
     * - server_port Server Remote port
     * - remote_port Client remote port
     * - remote_ip Client remote ip
     * - connect_time connection timestamp
     * - last_time last timestamp where data were sent
     *
     * @param int $fd
     * @param int $from_id
     * @return array|bool
     */
    public function connection_info($fd, $from_id = -1) {}

    /**
     * Get all client connections
     *
     * This method is based on a shared memory, there is no IOWait, iterate quickly.
     * Also connection_list returns all TCP connections, not just the current worker process TCP connection
     *
     * Example:
     *
     * ```php
     * $start_fd = 0;
     * while(true)
     * {
     *     $conn_list = $serv->connection_list($start_fd, 10);
     *     if($conn_list===false or count($conn_list) === 0)
     *     {
     *         echo "finish\n";
     *         break;
     *     }
     *     $start_fd = end($conn_list);
     *     var_dump($conn_list);
     *     foreach($conn_list as $fd)
     *     {
     *         $serv->send($fd, "broadcast");
     *     }
     * }
     * ```
     *
     * @param int $start_fd
     * @param int $pagesize
     * @return array | bool
     */
    public function connection_list($start_fd = -1, $pagesize = 100) {}

    /**
     * Reload all worker processes
     *
     * - SIGTERM: If this signal is sent to main process, the main server process will terminate gracefully. In php, call $serv->shutdown()
     * - SIGUSR1: If this signal is sent to management proces, all the workers process will be gracefully restart. In php, call $serv->reload().
     *
     * Swoole reload has a protective mechanism, when a reload is in progress, a new restart signal will be discarded
     *
     * ```php
     * # Restart all worker processes
     * kill -USR1 [MainProcessPID]
     * # Restart only all task worker processes
     * kill -USR2 [MainProcessPID]
     * ```
     *
     * For configuration that is set with $serv->set, you must close / restart the entire server before you can reload
     * Server can monitor a network port, and can receive remote control commands, to reset all worker
     *
     * @return bool
     */
    public function reload() {}

    /**
     * Shutdown server
     *
     * This function can be used in the worker process. Send SIGTERM to the main process will also work
     *
     * @return bool
     */
    public function shutdown() {}

    /**
     * Add a listenning port & protocol.
     *
     * call swoole_server::connection_info to know what is the incoming port for this request.
     * You can mix UDP/TCP on the same port
     *
     * - SWOOLE_TCP/SWOOLE_SOCK_TCP tcp ipv4 socket
     * - SWOOLE_TCP6/SWOOLE_SOCK_TCP6 tcp ipv6 socket
     * - SWOOLE_UDP/SWOOLE_SOCK_UDP udp ipv4 socket
     * - SWOOLE_UDP6/SWOOLE_SOCK_UDP6 udp ipv6 socket
     * - SWOOLE_UNIX_DGRAM unix socket dgram
     * - SWOOLE_UNIX_STREAM unix socket stream
     *
     *
     * Examples:
     *
     * ```php
     * $serv->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);
     * $serv->addlistener("192.168.1.100", 9503, SWOOLE_SOCK_TCP);
     * $serv->addlistener("0.0.0.0", 9504, SWOOLE_SOCK_UDP);
     * $serv->addlistener("/var/run/myserv.sock", 0, SWOOLE_UNIX_STREAM);
     * ```
     *
     * @param string $host
     * @param int $port
     * @param int $type
     */
    public function addlistener($host, $port, $type = SWOOLE_SOCK_TCP) {}

    /**
     * Get information about connections & task jobs
     *
     * ```php
     * array (
     *   'start_time' => 1409831644,
     *   'connection_num' => 1,
     *   'accept_count' => 1,
     *   'close_count' => 0,
     * );
     * ```
     *
     * - start_time : Server start time
     * - connection_num : Number of currently connected connections
     * - accept_count : Number of accepted connections
     * - close_count : Number of closed connections
     * - tasking_num : Number of task jobs in queues
     *
     * @return array
     */
    function stats() {}

    /**
     * Perform action after the specify time
     *
     * swoole_server::after is only 1 trigger deleted after the first execution
     * This method is an alias swoole_timer_after function
     *
     * @param int $after_time_ms in milliseconds < 86 400 000
     * @param mixed $callback_function executed after $after_time_ms, must be callable and callback does'nt accept any arguments
     * @param mixed $param
     */
    public function after($after_time_ms, $callback_function, $param = null) {}

    /**
     * Start listening on host:port:type
     *
     * @param $host
     * @param $port
     * @param $type
     * @return bool
     */
    public function listen($host, $port, $type = SWOOLE_SOCK_TCP) {}

    /**
     * Add of user defined workers process
     *
     * - $process is swoole_process objects. You don't need to start it, The process is automatically created when swoole_server starts. The child process can be created by calling various methods provided $server objects, such as connection_list / connection_info / stats
     * - In worker process, you can invoke methods provided for process and sub-process communication
     * - This function is typically used to create a special work processes for monitoring, reporting, or other special tasks.
     *
     * The child process will be hosted Manager process, if a fatal error occurs, manager process will re-create it
     *
     * @param swoole_process $process
     */
    public function addProcess(swoole_process $process) {}

    /**
     * Add a timer
     *
     * The timer is in millisecond & support for multiple timers.
     * This function can be used in worker process.
     *
     * Addtimer must only be used in onStart / onWorkerStart / onConnect / onReceive / onClose and other callback, otherwise it will throw an error and the timer will not be valid
     * Two identical timer will be forced in only one entry
     *
     * You need to set the onTimer callback, or Server will not start.
     * In this function, you need to watch the timer value in order to know what action must be done.
     *
     * ```php
     * // Object-oriented style
     * $serv->addtimer(1000); //1s
     * $serv->addtimer(20); //20ms
     * ```
     *
     * @param int $interval
     * @return bool
     */
    public function addtimer($interval) {}

    /**
     * Delete a timer
     *
     * @param int $interval
     */
    public function deltimer($interval) {}

    /**
     * Add a tick timer
     *
     * You can set a callback function.
     * This function is an alias swoole_timer_tick
     *
     * After the worker process finishes running, all timers will self-destruct.
     *
     * When you set tick interval, the callback function will be call after $interval_ms until you call swoole_timer_clear.   * The difference with swoole_timer_add is that multiple timers with same interval can exists.
     *
     * @param int $interval_ms
     * @param mixed $callback
     * @param mixed $param
     * @return int
     */
    public function tick($interval_ms, $callback, $param = null) {}

    /**
     * Clear a tick
     *
     * @param $id
     */
    function clearAfter($id) {}

    /**
     * Set server callback function
     *
     * like set and on, handler must be defined before swoole_server::start
     *
     * **Event List:**
     * - onStart
     * - onConnect
     * - onReceive
     * - onClose
     * - onShutdown
     * - onTimer
     * - onWorkerStart
     * - onWorkerStop
     * - onMasterConnect
     * - onMasterClose
     * - onTask
     * - onFinish
     * - onWorkerError
     * - onManagerStart
     * - onManagerStop
     * - onPipeMessage
     *
     * ```php
     * $serv->handler('onStart', 'my_onStart');
     * $serv->handler('onStart', array($this, 'my_onStart'));
     * $serv->handler('onStart', 'myClass::onStart');
     * ```
     *
     * @param string $event_name the name of the callback that is not case sensitive
     * @param mixed $event_callback_function PHP callback function, it can be a string, an array of anonymous functions
     * @return bool
     */
    public function handler($event_name, $event_callback_function) {}

    /**
     * Send files to a TCP client connections
     *
     * Call sendfile system call provided by the OS, read and write files directly from the operating system socket.
     * Sendfile only do 2 memory copy, use this function to send a large file can be reduced the operating system's CPU and memory footprint.
     *
     * This function like swoole_server->send are sending data to the client, except that sendfile specified file path.
     *
     * @param int $fd
     * @param string $filename file absolute path, if the file does not exist will return false
     * @return bool
     */
    public function sendfile($fd, $filename) {}

    /**
     * Bind a connection to workers process
     *
     * You can set dispatch_mode = 5 setting in order to stick an uid to a specific worker process.
     * In the default setting dispatch_mode = 2, server will dispatch socket fd connection to a different worker.
     * Because fd can be unstable (a client reconnects after disconnection), fd will change, so this client's data will be assigned to another Worker.
     * After using the bind & set a user-defined ID. Even reconnection, the same TCP connection will be assigned the same Worker process.
     *
     * If you have already call bind on a connection, calling again will returns false
     * You can use the $serv->connection_info($fd) to see the connection bound uid
     *
     * @param int $fd file descriptor connected
     * @param int $uid Specifies the UID
     * @return bool
     */
    public function bind($fd, $uid) {}
}

/**
 * Create a memory table
 *
 * @package Swoole
 */
class swoole_table
{
    const TYPE_INT = 1;
    const TYPE_STRING = 2;
    const TYPE_FLOAT = 3;

    /**
     * Get a key
     *
     * @param string $key
     * @return array
     */
    function get($key) {}

    /**
     * Set a key
     *
     * @param string $key
     * @param array $array
     */
    function set($key, array $array) {}

    /**
     * Delete a key
     *
     * @param $key
     * @return bool
     */
    function del($key){}

    /**
     * Atomic increment operation, can be used for int or float column
     *
     * @param string $key
     * @param string $column
     * @param int $incrby
     * @return bool
     */
    function incr($key, $column, $incrby = 1) {}

    /**
     * Atomic decrement operation, can be used for int or float column
     *
     * @param string $key
     * @param string $column
     * @param int $decrby
     */
    function decr($key, $column, $decrby = 1) {}

    /**
     * Add column to current table
     *
     * @param string $name
     * @param int $type swoole_table::TYPE_INT, swoole_table::TYPE_STRING, swoole_table::TYPE_FLOAT
     * @param int $len
     */
    function column($name, $type, $len = 4) {}

    /**
     * Create table in the operating system memory
     *
     * @return bool
     */
    function create() {}

    /**
     * Lock the entire table
     *
     * @return bool
     */
    function lock() {}

    /**
     * Release the table locks
     *
     * @return bool
     */
    function unlock() {}
}

/**
 * Websocket server
 *
 * @package Swoole
 */
class swoole_websocket_server extends swoole_http_server
{
    /**
     * WebSocket method to push data to client
     *
     * @param resource $fd
     * @param string $data
     * @param bool $binary_data
     * @param bool $finish
     */
    public function push($fd, $data, $binary_data = false, $finish = true) {}
}

