<?php

define("PROJECT_ROOT", dirname(__DIR__));


require_once( PROJECT_ROOT."/.vendor/autoload.php");
$dotenv = Dotenv\Dotenv::createImmutable(PROJECT_ROOT);
$dotenv->load();

define("DB_USER", $_ENV["DB_USER"]);
define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_PASS", $_ENV["DB_PASS"]);
define("DB_NAME", $_ENV["DB_NAME"]);
