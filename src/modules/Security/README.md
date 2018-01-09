# M2M - Security

Request Header have to provide X-Auth-Token to authenticate machine. That is the default implemented security module by Objex. This module is build on top of some Symfony components of security component.
It is possible to write another Extension on top of this component if needed - cause HTTPKernel is also a Symfony component, all events are available.

I've choose this M2M structure to provide an easy and fast way to ensure authentication for the api server, being not dependent to create an user object with an username and password, so I named it machine which
only have a name and an api key. For example you hve a frontend which will use Objex as API server to manage database storage and external HTTP callings - you can communicate with an api key - the rest of the logic can
later being implemented at defining schema and event listeners.   

## configure guarded urls

at config/firewall.php you can define the urlMap to tell the system which urls have to be protected - default is set to all.

```php
[
    'urlMap' => [
        '^/'
    ]
]
```