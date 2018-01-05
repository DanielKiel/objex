## not-production-ready
this is only some firts coding stuff - do not use it in production, it can have a lot of breaks till it will be alpha and later beta ready


## idea behind
this should be get a microframework to handle an api server  


## first hints about what to do

configure the database: 
src/config/database.php

install the database:
//want to see the sql first?
php vendor/bin/doctrine orm:schema-tool:update --dump-sql

//want to install?
php vendor/bin/doctrine orm:schema-tool:update --dump-sql --force

this will not do any migration yet - it is some natural doctrine handling here, not less or more