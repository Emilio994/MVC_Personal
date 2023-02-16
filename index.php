<?php
session_start();
require_once('app/helpers/functions.php');
require_once('app/config/path_names.php');
require_once('app/config/db_connection.php');
require_once('app/config/db_connection_wp.php');
require_once('vendor/autoload.php');

use Frame\App;
use Frame\Configuration\Database;
use Frame\Configuration\DatabaseWP;

$db = Database::singleton();
$wpDb = DatabaseWP::singleton();

$app = App::singleton();

$app->listen();

$app->response();
