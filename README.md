## not-production-ready
this is only some firts coding stuff - do not use it in production, it can have a lot of breaks till it will be alpha and later beta ready


## idea behind
this should be get a microframework to handle an api server  

objex() will be your friend :) with this you will get access to the core, the dependency injection definitions like

- objex()->get('app') // the instance of App.php
- objex()->get('orm') //doctrine orm

Objex will have a lot of helper functions integrated - the aim is to define objects fast, without 
thinking about the objects and services behind

```php

//create and update an object schema
setSchema('MyNamespace', [
    'foo' => 'bar'
]);

//get an object schema
getSchema('MyNamespace');

//delete an object schema
deleteSchema('MyNamespace');

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