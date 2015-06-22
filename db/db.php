<?php

$di = new \Phalcon\DI\FactoryDefault();

//Set up the database service
$di->set('db', function(){
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => "127.0.0.1",
        "username" => "root",
        "password" => "11235813",
        "dbname" => "phalcon"
    ));
});

?>