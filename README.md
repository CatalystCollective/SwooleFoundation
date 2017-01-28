# SwooleFoundation
Swoole Foundation Library

### What is the Swoole Foundation

The swoole foundation is a factory foundation to easily create swoole
server instances. The orchestration of settings and events is closure
based.

### Dependencies

This Package depends on `catalyst/servant`.

### Creating a Swoole Server

```php
use Catalyst\Swoole\{
    ServerFactory,
    Entities\ServerEvents,
    Entities\ServerConfiguration
};

$factory = new ServerFactory();

$factory->server(function(ServerConfiguration $settings, ServerEvents $events) {
    $settings->withBinding('::', 9508);
    
    $events->onStart(function() {
        echo 'Server started!';
    });
});
```

### Creating a Swoole Http Server

```php
use Catalyst\Swoole\{
    ServerFactory,
    Entities\HttpServerEvents,
    Entities\ServerConfiguration
};

$factory = new ServerFactory();

$factory->httpServer(function(ServerConfiguration $settings, HttpServerEvents $events) {
    $settings->withBinding('::', 80);
    
    $events->onRequest(function(swoole_http_request $request, swoole_http_respose $response) {
        $response->end('Hello World!');
    });
});
```

### Creating a Swoole Websocket Server

```php
use Catalyst\Swoole\{
    ServerFactory,
    Entities\HttpServerEvents,
    Entities\ServerConfiguration
};

$factory = new ServerFactory();

$factory->webSocketServer(function(ServerConfiguration $settings, HttpServerEvents $events) {
    $settings->withBinding('::', 80);
    
    $events->onRequest(function(swoole_http_request $request, swoole_http_respose $response) {
        $response->end('Hello World!');
    });
});
```

### Servant awareness

The Factory implementation is servant aware and allows to chain additional
servants to the given servants. Due to implementation decisions, you can not
replace the first servant. An exception will be thrown when you try to set
a different servant.

### License and Maintainer(s)

This package is licensed under the MIT license. This package is actively
maintained by:

- Matthias Kaschubowski