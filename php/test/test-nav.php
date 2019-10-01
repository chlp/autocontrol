<?php

require __DIR__ . '/../navision_wsdl/autoload.php';
require __DIR__ . '/../Config.php';
require_once(__DIR__ . '/../Mysql.php');
require_once(__DIR__ . '/../include/NavisionWsdl/autoload.php');

$db = Mysql::init();
$navision = new nav\DepartureCar1(
    Config::NAVISION_LOGIN,
    Config::NAVISION_PASSWORD,
    $db->getOne("SELECT `value` FROM `settings` WHERE `name` = 'navision'"),
    __DIR__ . '/navision.wsdl'
);

$p = new nav\CheckSLCar2('1', '2', '3', '10', '10', '', '0', 0, '');
$r = $navision->CheckSLCar2($p);
echo 'CheckSLCar2:<br>';
var_dump($r);
echo '<br><br>';
echo 'OpenStageBeforeKPP:<br>';
$p = new nav\OpenStageBeforeKPP('', '');
$r = $navision->OpenStageBeforeKPP($p);
var_dump($r);

// object(nav\CheckSLCar1_Result)#3 (5) { ["return_value":protected]=> bool(false) ["autoWeit":protected]=> string(2) "10" ["itemWeit":protected]=> string(2) "10" ["errorText":protected]=> string(45) "Нет такого пользователя." ["directionOfMotion":protected]=> string(1) "0" }