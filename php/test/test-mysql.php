<?php
require_once(__DIR__ . '/../Mysql.php');
$db = Mysql::init();
var_dump($db->assoc("SELECT * FROM visits LIMIT 2;"));