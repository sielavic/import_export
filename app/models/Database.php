<?php
namespace Models;
use Illuminate\Database\Capsule\Manager as Capsule;
class Database {
    function __construct() {
        $capsule = new Capsule;
        $capsule->addConnection([
            "driver" => DBDRIVER,
            "host" => DBHOST,
            "database" => DBNAME,
            "username" => DBUSER,
            "password" => DBPASS,
            "charset" => "utf8mb4",
            "collation" => "utf8mb4_general_ci",
            "prefix" => "",
        ]);

        $capsule->bootEloquent();
    }
}
