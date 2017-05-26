# log-exceptions [![Build Status](https://travis-ci.org/php-middleware/log-exceptions.svg?branch=master)](https://travis-ci.org/php-middleware/log-exceptions)

Log all exceptions from your middlewares

When your middleware throw exception this middleware catch it, log it and throw again. You will newer miss any exception.

## Installation

```
composer require php-middleware/log-exceptions
```

To build this middleware you need to injecting inside `PhpDebugBarMiddleware` instance of any `Psr\Log\LoggerInterface` implementation:

```php
$logger = new LoggerImplementation();
$middleware = new PhpMiddleware\LogException\LogExceptionMiddleware($logger);
```

and add it into your middleware dispatcher.
