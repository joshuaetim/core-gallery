<?php declare(strict_types=1);

namespace BlogCore\Handlers;

use \Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public $capsule;

    public function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'blogcore',
            'username'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}