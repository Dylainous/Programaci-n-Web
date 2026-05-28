<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'pgsql',
    'url'      => 'postgresql://postgres.syiqftmpmmpvjbpmqspp:Alexander12.Dalmatax1011@aws-1-us-east-2.pooler.supabase.com:6543/postgres',
    'charset'  => 'utf8',
    'prefix'   => '',
    'sslmode'  => 'require',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
