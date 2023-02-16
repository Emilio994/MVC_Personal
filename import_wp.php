<?php

require_once('app/helpers/functions.php');
require_once('app/config/path_names.php');
require_once('app/config/db_connection.php');
require_once('app/config/db_connection_wp.php');
require_once('vendor/autoload.php');

use Frame\Configuration\Database;
use Frame\Configuration\DatabaseWP;

$db = Database::singleton();
$wpDb = DatabaseWP::singleton();

$parkings = $wpDb->query(
    "SELECT * 
    FROM wp_posts
    WHERE post_type = 'cpbs_location'
    AND post_status = 'publish'"
)->fetchAll();


$values = '';

$db->query("TRUNCATE TABLE parkings")->execute();

foreach($parkings as $index => $park) {
    if($index < (count($parkings) - 1) ) $values .= "('$park->post_title','$park->post_name'),";
    else $values .= "('$park->post_title','$park->post_name');";
}

$localImport = $db->prepare(
    "INSERT INTO parkings (nome,slug)
    VALUES $values"
);

$localImport->execute();
