<?php
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

$capsule = new Capsule();

$files = ['default'];

foreach ($files as $file) {
    $db_ini = parse_ini_file('app/config/'.$file.".ini");
    $capsule->addConnection([
        'driver'    => $db_ini['type'],
        'host'      => $db_ini['host'],
        'database'  => $db_ini['name'],
        'username'  => $db_ini['user'],
        'password'  => $db_ini['pass'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ], $file);
}

$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();

$capsule->bootEloquent();