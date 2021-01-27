<?php 

require_once dirname(__DIR__).'/vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as Capsule;

// boot connection

new \BlogCore\Models\Database();

Capsule::schema()->create('photos', function($table)
{
    $table->increments('id');
    $table->string('title');
    $table->text('description');
    $table->text('photo');
    $table->text('thumbnail');
    $table->timestamps();
});