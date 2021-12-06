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
            'host'      => '127.0.0.1',
            'port'      => 3306,
            'unix_socket' => getenv('DB_SOCKET'),
            'database'  => 'blogcore', // edit for your unique database name
            'username'  => 'root',
            'password'      => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'sslmode' => 'require',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}