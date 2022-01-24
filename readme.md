# Factoring004 SDK

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    * [Create transport instance](#create-transport-instance)
    * [Create api instance](#create-api-instance)
    * [Call endpoints](#call-endpoints)
    * [Error handling](#error-handling)
    * [Exception Hierarchy](#exception-hierarchy)
- [Advanced usage](#advanced-usage)
    * [Resources](#resources)
    * [Transport layer](#transport-layer)

## Requirements

- PHP >=7.4
- JSON extension
- PSR-17, PSR-18 implementations

## Installation

First install any PSR-17 and PSR-18 packages.
See [PSR-17 packages](https://packagist.org/providers/psr/http-factory-implementation) and
[PSR-18 packages](https://packagist.org/providers/psr/http-client-implementation).

For instance, we install popular Guzzle HTTP client. Since version 7 it has already implemented PSR-17 and PSR-18.

```bash
composer require guzzlehttp/guzzle
```

If you are using Guzzle 6 you should install PSR-17 and PSR-18 adapters also.

```bash
composer require http-interop/http-factory-guzzle mjelamanov/psr18-guzzle
```

Finally, install the package.

```bash
composer require bnpl-partners/factoring004
```

## Usage

### Create transport instance

For Guzzle 7 client.

```php
use BnplPartners\Factoring004\Transport\Transport;

require_once __DIR__ . '/vendor/autoload.php';

$transport = new Transport(
    new GuzzleHttp\Psr7\HttpFactory(),
    new GuzzleHttp\Psr7\HttpFactory(),
    new GuzzleHttp\Psr7\HttpFactory(),
    new GuzzleHttp\Client(),
);
```

For Guzzle 6 client.

```php
use BnplPartners\Factoring004\Transport\Transport;

require_once __DIR__ . '/vendor/autoload.php';

$transport = new Transport(
    new Http\Factory\Guzzle\RequestFactory(),
    new Http\Factory\Guzzle\StreamFactory(),
    new Http\Factory\Guzzle\UriFactory(),
    new Mjelamanov\GuzzlePsr18\Client(new GuzzleHttp\Client()),
);
```

### Create api instance

```php
use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;

require_once __DIR__ . '/vendor/autoload.php';

$api = Api::create($transport, 'http://api-domain.com', new BearerTokenAuth('Access Token'));
```

### Call endpoints

#### PreApp

Create preApp

```php
use BnplPartners\Factoring004\PreApp\PreAppMessage;
use BnplPartners\Factoring004\PreApp\PartnerData;

$message = new PreAppMessage(
    new PartnerData('test', 'test', 'test'),
    '1',
    6000,
    1,
    'http://your-store.com/success',
    'http://your-store.com/internal',
);

// Or
$message = PreAppMessage::createFromArray([
    'partnerData' => [
        'partnerName' => 'test',
        'partnerCode' => 'test',
        'pointCode' => 'test',
    ],
    'billNumber' => '1',
    'billAmount' => 6000,
    'itemsQuantity' => 1,
    'successRedirect' => 'http://your-store.com/success',
    'postLink' => 'http://your-store.com/internal',
]);

//Send request and receive response
$response = $api->preApps->preApp($message);

var_dump($response->getStatus(), $response->getPreAppId(), $response->getRedirectLink());
var_dump($response->toArray(), json_encode($response));
```

#### OTP

Send OTP

```php
use BnplPartners\Factoring004\Otp\SendOtp;

$sendOtp = new SendOtp('1', '1');

// or
$sendOtp = SendOtp::createFromArray(['merchantId' => '1', 'merchantOrderId' => '1']);

// send request and receive response
$response = $api->otp->sendOtp($sendOtp);

var_dump($response->getMsg());
var_dump($response->toArray(), json_encode($response));
```

Check OTP

```php
use BnplPartners\Factoring004\Otp\CheckOtp;

$checkOtp = new CheckOtp('1', '1', '1111');

// or
$checkOtp = CheckOtp::createFromArray(['merchantId' => '1', 'merchantOrderId' => '1', 'otp' => '1111']);

// send request and receive response
$response = $api->otp->checkOtp($checkOtp);

var_dump($response->getMsg());
var_dump($response->toArray(), json_encode($response));
```

### Error handling

Whenever api returns an error client will throw an instance of ``BnplPartners\Factoring004\Exception\ApiException``.

```php
use BnplPartners\Factoring004\Exception\ApiException;
use BnplPartners\Factoring004\Exception\AuthenticationException;
use BnplPartners\Factoring004\Exception\EndpointUnavailableException;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\NetworkException;
use BnplPartners\Factoring004\Exception\TransportException;
use BnplPartners\Factoring004\Exception\UnexpectedResponseException;
use BnplPartners\Factoring004\Exception\ValidationException;

try {
    $api->preApps->preApp($message);
} catch (AuthenticationException $e) {
    // authentication errors, token invalid, token expired, etc
    var_dump($e->getCode(), $e->getMessage(), $e->getDescription());
} catch (EndpointUnavailableException $e) {
    // to catch all internal server errors 500, 503, etc
    var_dump($e->getResponse());
} catch (ErrorResponseException $e) {
    // to catch all client errors 400, 405, etc
    var_dump($e->getErrorResponse());
} catch (UnexpectedResponseException $e) {
    // to catch all responses with unexpected schema
    var_dump($e->getResponse());
} catch (ValidationException $e) {
    // endpoint validation error
    $details = $e->getResponse()->getDetails();
    var_dump($details[0]->getError(), $details[0]->getField());
} catch (ApiException $e) {
    // to catch all api layer exceptions
} catch (NetworkException $e) {
    // network issues, connection refused, etc
} catch (TransportException $e) {
    // to catch all transport layer exceptions
}
```

## Exception Hierarchy

- ``PackageException``
  * ``ApiException``
    * ``AuthenticationException``
    * ``ValidationException``
    * ``ErrorResponseException``
    * ``UnexpectedResponseException``
      * ``EndpointUnavailableException``
  * ``TransportException``
    * ``NetworkException``
    * ``DataSerializationException``

## Advanced usage

### Resources

Each resource is a set of grouped endpoints.

```php
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Otp\OtpResource;
use BnplPartners\Factoring004\PreApp\PreAppResource;

require_once __DIR__ . '/vendor/autoload.php';

...

$preApp = new PreAppResource($transport, 'http://api-domain.com', new BearerTokenAuth('Access Token'));
$response = $preApp->preApp(...);

$otp = new OtpResource($transport, 'http://api-domain.com', new BearerTokenAuth('Access Token'));
$response = $otp->sendOtp(...);
```

### Transport layer

Transport is an abstraction layer over HTTP clients.

```php
use BnplPartners\Factoring004\Transport\Transport;

require_once __DIR__ . '/vendor/autoload.php';

$transport = new Transport(
    new RequestFactory(), // PSR-17 instance
    new StreamFactory(), // PSR-17 instance
    new UriFactory(), // PSR-17 instance
    new Client() // PSR-18 instance
);

$response = $transport->post('/bnpl-partners/1.0/preapp', ['partnerData' => [...]], ['Content-Type' => 'application/json']);

var_dump($response->getStatusCode()); // HTTP response status code
var_dump($response->getHeaders()); // HTTP response headers
var_dump($response->getBody()); // parsed HTTP response body
```

You can create your own transport. Just implement ``BnplPartners\Factoring004\Transport\TransportInterface``.

## Test

```bash
./vendor/bin/phpunit
```