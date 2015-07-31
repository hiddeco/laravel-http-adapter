Laravel HTTP Adapter
====================
Laravel HTTP Adapter is an [Ivory HTTP Adapter](https://github.com/egeloen/ivory-http-adapter) bridge, which
integrates the ability of using multiple adapters with their own configuration through one API.

## Installation
To use this package without running into trouble you will need PHP 5.5+ or HHVM 3.6+, and Composer.

1.	Get the latest version of Laravel HTTP Adapter, add the following line to your composer.json file
	`"hiddeco/laravel-http-adapter": "0.1"`
	
	The package supports the following adapters, you will need to install the ones you want to use.
	
	- `"ext-curl": "*"`
	- `"ext-http": "*"`
	- `"guzzle/guzzle": "^3.9.4@dev"`
	- `"guzzlehttp/guzzle": "^4.1.4|^5.0"`
	- `"kriswallsmith/buzz": "^0.13"`
	- `"nategood/httpful": "^0.2.17"`
	- `"zendframework/zendframework1": "^1.12.9"`
	- `"zendframework/zend-http": "^2.3.4"`

2.	Run `composer update` or `composer install`

3.	Register the Laravel HTTP Adapter service provider in `config/app.php` by adding 
	`'HiddeCo\HttpAdapter\HttpAdapterServiceProvider'` to the providers key

4.	Add the `HttpAdapter` facade to the aliases key: `'HttpAdapter' => 'HiddeCo\HttpAdapter\Facades\HttpAdapter'`

## Configuration
To manage your HTTP adapters run the `php artisan vendor:publish` command, this will create the `config/httpadapter.php`
file where you can modify and manage your adapter connections.

The following configuration options are available:

**Global Configuration**

The Global Configuration (`global`) overrules all the other configurations set for your HTTP adapters. It accepts the 
same parameters as an HTTP adapter connection does.

**Default Connection Name**

The adapter connection name set here (`default`) is the default connection used for all requests. However, you may use as
many connections as you need using the manager class. The default setting is `'main'`.

**HTTP Adapter Connections**

This is the place to configure your HTTP adapter connections (`connections`). A default configuration with possible 
options is already present and there is no limit to the amount of connections.

The following adapters are available: `buzz`, `cake`, `curl`, `file_get_contents`, `fopen`, `guzzle`, `guzzle_http`,
`httpful`, `react`, `socket`, `zend1` and `zend2`.

## Usage
### HTTP Adapter Manager
The `HttpAdapterManager` is where the magic happens. Bounded to the ioc container as `httpadapter` and accessible by using the 
`Facade\HttpAdapter` facade. It uses parts of the [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) 
package to manage the HTTP adapter connections. For more information about the Manager you should check out the respective 
[docs](https://github.com/GrahamCampbell/Laravel-Manager#usage). 

It is worth noting the connection returned will always be an instance of `\Ivory\HttpAdapter\HttpAdapterInterface`. You 
can find more information about this instance and its methods in the [Ivory HTTP Adapter docs](https://github.com/egeloen/ivory-http-adapter/blob/master/doc/usage.md).

### HTTP Adapter Facade
The HTTP Adapter facade will pass static method calls to the `httpadapter` object in the ioc container, which as stated 
before is the `HttpAdapterManager` class.

### Examples
The usage of this package is fairly simple. The `main` connection is preconfigured and uses the `file_get_contents` HTTP 
adapter, no other configuration options are set for this connection by default.

**Using the Facade**

````php
use HiddeCo\HttpAdapter\Facades\HttpAdapter;

$request = HttpAdapter::get('http://awesome.com');
$body = $request->getBody();
// and you're done
````

**Using the HTTP Adapter Manager**

The `HTTPAdapterManager` returns an instance of `\Ivory\HttpAdapter\HttpAdapterInterface` and will behave like it. If 
you want to call a specific connection, you can use the `connection` method:

````php
use HiddeCo\HttpAdapter\Facades\HttpAdapter;

$request = HttpAdapter::connection('alternative')->get('http://awesome.com');
````

Changing the default connection and further explanations:

````php
use HiddeCo\HttpAdapter\Facades\HttpAdapter;

HttpAdapter::connection('main')->get('http://awesome.com');
HttpAdapter::get('http://awesome.com');
HttpAdapter::connection()->get('http://awesome.com');
// are all the same because 

HttpAdapter::getDefaultConnection();
// returns 'main' as set in the configuration file

HttpAdapter::setDefaultConnection('alternative');
// the 'alternative' connection is now the default connection
````

**Dependency Injection**

Prefer the use of a dependency injection over facades? You can easily inject the manager:

````php
use HiddeCo\HttpAdapter\HttpAdapterManager;

class Foo
{
	protected $httpadapter;
	
	public function __construct(HttpAdapterManager $httpadapter)
	{
		$this->httpadapter;
	}
	
	public function bar()
	{
		$this->httpadapter->get('http://awesome.com');
	}
}
````

### Events
If you set the `eventable` key in the general or local HTTP adapter configuration to `true` the `\Ivory\HttpAdapter\EventDispatcherHttpAdapter` 
will be used to make your HTTP adapter connection. This way it's possible to listen to events that are dispatched using 
the Laravel dispatcher. All available events can be found in the [Ivory HTTP docs](https://github.com/egeloen/ivory-http-adapter/blob/0.7.1/doc/events.md#events).

````php
use Ivory\HttpAdapter\Event\Events;
use Ivory\HttpAdapter\Event\PostSendEvent;

Event::listen(Events::POST_SEND, function (PostSendEvent $event) {
	Log::info("A request was made");
});
````

It is also possible to register the event listener using the `EventServiceProvider`.

## License
Laravel HTTP Adapter is licensed under the MIT License (MIT).

