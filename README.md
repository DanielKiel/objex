## not-production-ready
this is only some firts coding stuff - do not use it in production, it can have a lot of breaks till it will be alpha and later beta ready

At the moment this framework is build on top of a lot of symfony4 components - but it will not be dpendend to use only components of symfony.
The aim is to really simple can include other packages too.
You must only decide on which event(s) you want to listen for.

## idea behind
this should be get a microframework to handle an api server  

objex() will be your friend :) with this you will get access to the core, the dependency injection definitions like

- objex()->get('app') // the instance of App.php
- objex()->get('orm') //doctrine orm

Objex will have a lot of helper functions integrated - the aim is to define objects fast, without 
thinking about the objects and services behind

### schema handling

```php
//create and update an object schema
setSchema('MyNamespace', [
    'foo' => 'bar'
]);
```

```php
//get an object schema
getSchema('MyNamespace');
```

Be careful what you do: deleting a schema means deleting all objects which belongs to this schema!!!

```php
//delete an object schema
deleteSchema('MyNamespace');
```


### object handling

make an upsert with save:

```php
$obj = saveObject('MyNamespace', [
    'foo' => 'bar'
]);
```

After this you have access to the properties with:

```php
$obj->id;
$obj->foo;
```

deleting an object:

```php
deleteObj('MyNamespace', 5);
```
## first hints about what to do

configure the database: 
src/config/database.php

install the database:
//want to see the sql first?
php vendor/bin/doctrine orm:schema-tool:update --dump-sql

//want to install?
php vendor/bin/doctrine orm:schema-tool:update --dump-sql --force

this will not do any migration yet - it is some natural doctrine handling here, not less or more


## Modules

modules are registered at config/modules.php. An example to register on booting by defined class:
```php

namespace Objex\Validation;


use Objex\Core\Events\Booting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ValidatorService implements EventSubscriberInterface
{
    public function onBooting(Booting $event)
    {
        $event->getServiceContainer()->get('orm')
            ->getEventManager()
            ->addEventSubscriber(new Validator());
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'onBooting'
        ];
    }
}

```

There are a lot of default symfony events where you can listen to.

### Validation

When defining a schema, you can do for example:

```php
    setSchema('MyNamespace',[
        'definition' => [
            'foo' => [
                'type' => 'text',
                'validation' => 'strlen(foo) > 3'
            ],
            'bar' => [
                'type' => 'text',
                'validation' => 'strlen(bar) < 3',
                'errormessage' => 'bar must not have more than 2 signs'
            ]
        ]
    ]);
```

it is using symfony expression language, documented here: https://symfony.com/doc/master/components/expression_language/syntax.html

@dev: we can register here php functions and more at LanguageDefinition, at the moment at Objex\Validation\Rules, this should get extendable later

Also it is possible to define some "basic" symfony constraints, like here: https://symfony.com/doc/master/validation.html

```php
setSchema('MyNamespace',[
    'definition' => [
        'foo' => [
            'type' => 'text',
            'validation' => [
                new \Symfony\Component\Validator\Constraints\Length(['min' => 5]),
                new \Symfony\Component\Validator\Constraints\Email()
            ]
        ],
        'bar' => [
            'type' => 'text',
            'validation' => new \Symfony\Component\Validator\Constraints\Length(['max' => 2]),
            'errormessage' => 'bar must not have more than 2 signs'
        ]
    ]
]);
```

## actual code quality

phpmetrics is integrated at dev so for example type:

```
phpmetrics --report-html=.codereport ./src
```

## next steps:
make a more modular architecture !!! expression language now is dependent to validation - must get a service, but therefor we must have
something like a service checker - so a module must define whcih services it needs to can run and a function must exists which will loop through modules
to detect modules which can not run cause services are not available

## wanted syntax to define an api server only with json

```json
{
  "MyNamspace" : {
    "alias": "myAlias",
    "definition" : {
      "foo" : {
        "type": "text",
        "validation": "strlen(foo) > 5 and strpos(foo, 'needed') !== false"   //basic expressions of php included for validation definition
      }
    },
    "onAfterCreate": "HTTP.post('https://externalAPI.com', myAlias)" //define events here onAfterCreate, onAfterUpdate, onAfterDelete, onBeforeCreate, onBeforeUpdate, onBeforeDelete, ... 
  }
}
```