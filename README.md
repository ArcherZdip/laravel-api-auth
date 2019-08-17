# Laravel-api-auth

[![star this repo](http://githubbadges.com/star.svg?user=ArcherZdip&repo=laravel-api-auth&style=default)](https://github.com/ArcherZdip/laravel-api-auth)
[![fork this repo](http://githubbadges.com/fork.svg?user=ArcherZdip&repo=laravel-api-auth&style=default)](https://github.com/ArcherZdip/laravel-api-auth/fork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/ArcherZdip/laravel-api-auth/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## Installation
Run `composer require archerzdip/laravel-api-auth`.

Publish the migration files

    $ php artisan vendor:publish

Run the migrations

    $ php artisan migrate

## Console
Generate a new app using `php artisan apikey:generate {name}`. The name argument is the name of your APP name.  All new app are active by default.
 ```bash
☁  demo1  php artisan apikey:generate demo-app1
+-----------+------------------+------------------------------------------------------------------+---------------------+
| AppName   | appId            | secret                                                           | CreateAt            |
+-----------+------------------+------------------------------------------------------------------+---------------------+
| demo-app1 | JNzgjqLnp1nLNCBV | G8sfHyguwhB7mGTpdp0LCBEooZPOFnzlqHX8NRCZSG7miWwPRihNw4vsmcSeYChq | 2019-08-16 06:50:08 |
+-----------+------------------+------------------------------------------------------------------+---------------------+

```

About app opreate, like activate, deactivate, delete, refresh secret.

```bash
☁  demo1  php artisan apikey:put --help        
Usage:
  apikey:put [options] [--] <appid>

Arguments:
  appid                 

Options:
  -A, --activate        Activate an App by appid
  -F, --deactivate      Deactivate an App by appid
  -D, --delete          Delete an App by appid
  -R, --refresh         refresh an app's secret by appid
  -h, --help            Display this help message
```

Deactivate app by appid using `php artisan apikey:put {appid} -F`

```bash
☁  demo1  php artisan apikey:put eA4lU1ukEWZkdmAb -F
Deactivate app succ, name: demo-app2
+-----------+------------------+------------------------------------------------------------------+-------------+---------------------+
| AppName   | AppId            | Secret                                                           | Status      | CreateAt            |
+-----------+------------------+------------------------------------------------------------------+-------------+---------------------+
| demo-app2 | eA4lU1ukEWZkdmAb | CxDWa7uFxgGhshmbgm0HE9bqRbVN1gj0CO47pdwZzXpWhfuebvULfUwmnCPK59ph | deactivated | 2019-08-16 06:59:06 |
+-----------+------------------+------------------------------------------------------------------+-------------+---------------------+

```

Activate app by appid using `php artisan apikey:put {appid} -A`

```bash
☁  demo1  php artisan apikey:put eA4lU1ukEWZkdmAb -A
Activate app succ, name: demo-app2
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+
| AppName   | AppId            | Secret                                                           | Status | CreateAt            |
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+
| demo-app2 | eA4lU1ukEWZkdmAb | CxDWa7uFxgGhshmbgm0HE9bqRbVN1gj0CO47pdwZzXpWhfuebvULfUwmnCPK59ph | active | 2019-08-16 06:59:06 |
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+
```

Refresh secret by appid using `php artisan apikey:put {appid} -R`
```bash
☁  demo1  php artisan apikey:put eA4lU1ukEWZkdmAb -R

 Are you sure you want to refresh this app secret, AppId:eA4lU1ukEWZkdmAb, name:demo-app2 ? (yes/no) [no]:
 > no 

☁  demo1  php artisan apikey:put eA4lU1ukEWZkdmAb -R

 Are you sure you want to refresh this app secret, AppId:eA4lU1ukEWZkdmAb, name:demo-app2 ? (yes/no) [no]:
 > yes

Refresh app secret succ, name: demo-app2
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+
| AppName   | AppId            | Secret                                                           | Status | CreateAt            |
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+
| demo-app2 | eA4lU1ukEWZkdmAb | A6oMUTU4XZDExbxVLGdwNdbptdKe6ewNivCloDXsRTYGTQfjCZVqMQUeiq651Zq0 | active | 2019-08-16 06:59:06 |
+-----------+------------------+------------------------------------------------------------------+--------+---------------------+

```

Delete app by appid using `php artisan apikey:put {appid} -D`

```bash
☁  demo1  php artisan apikey:put JNzgjqLnp1nLNCBV -D

 Are you sure you want to delete AppId:JNzgjqLnp1nLNCBV, name:demo-app1 ? (yes/no) [no]:
 > yes

Deleted app succ, name: demo-app1
```

List all app. The -D or --deleted flag includes deleted apps.
```bash
☁  demo1  php artisan apikey:list -D
+-----------+------------------+------------------------------------------------------------------+---------+---------------------+
| AppName   | AppId            | Secret                                                           | Status  | CreateAt            |
+-----------+------------------+------------------------------------------------------------------+---------+---------------------+
| demo-app1 | JNzgjqLnp1nLNCBV | G8sfHyguwhB7mGTpdp0LCBEooZPOFnzlqHX8NRCZSG7miWwPRihNw4vsmcSeYChq | deleted | 2019-08-16 06:50:08 |
| demo-app2 | eA4lU1ukEWZkdmAb | A6oMUTU4XZDExbxVLGdwNdbptdKe6ewNivCloDXsRTYGTQfjCZVqMQUeiq651Zq0 | active  | 2019-08-16 06:59:06 |
+-----------+------------------+------------------------------------------------------------------+---------+---------------------+

```
## Usage
A new `auth.apikey` route middleware has been registered for you to use in your routes or controllers.  Below are examples on how to use middleware, but for detailed information, check out [Middleware](https://laravel.com/docs/middleware) in the Laravel Docs.

Route example

```php
Route::get('api/user/1', function () {
    //
})->middleware('auth.apikey');

```

Controller example

```php
class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.apikey');
    }
}
```

### Authorizing Requests

In order to pass the `auth.apikey` middleware, requests must include an `Authorization` header as part of the request, with its value being an active API key.

    Authorization: VApUyoTm5I5DtlQAJjJbmCbrdceFsVCb6H3CpsL4SdUlgGdUui8WjxwbcejAfmL7  

or `token={token}`       

Token generate regulation.  
```php
sign = sha1(appid . secret . exp) // exp = time()
token = base64_encode(implode('.', [appid, sign, exp])
```
                                                                                                                                                                                                                                                                                                                                                                                                                                                          
## Event history
Any time an API key is generated, activated, deactivated, or deleted, a record is logged in the `api_auth_oprate_event` table.  Each record contains the following information:
* app_client_id
* ip_address
* event
* created_at
* updated_at

## API event history
If you open log, all API requests that pass authorization will logger.
Support driver database and file.
Database infomation:
* appid
* ip_address
* url
* params
* type

## TODO
- Configuration exception and return format.
- Configuration way of encryption.

## License
MIT license