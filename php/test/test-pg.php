<?php

require_once(__DIR__ . '/../PgClass.php');
require_once(__DIR__ . '/../Config.php');
$db = new PgClass(Config::PLATERECOGNIZER_DB, Config::PLATERECOGNIZER_LOGIN, Config::PLATERECOGNIZER_PASSWORD, Config::PLATERECOGNIZER_HOST);
$r = $db->getOne('SELECT "trstime" FROM "in" ORDER BY "trstime" DESC LIMIT 1;');
var_dump($r);