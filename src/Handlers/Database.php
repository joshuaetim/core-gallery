<?php declare(strict_types=1);

namespace BlogCore\Handlers;

use \Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public $capsule;

    public function __construct()
    {
        $capsule = new Capsule;

        $DATABASE_URL = parse_url(getenv("DATABASE_URL"));

        $capsule->addConnection([
            'driver'    => 'pgsql',
            'host'      => $DATABASE_URL["host"],
            'port'      => $DATABASE_URL["port"],
            'database'  => ltrim($DATABASE_URL["path"], "/"), // edit for your unique database name
            'username'  => $DATABASE_URL["user"],
            'password'      => $DATABASE_URL["pass"],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'sslmode' => 'require',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}