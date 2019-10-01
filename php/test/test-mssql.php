<?php
require(__DIR__ . '/../Mssql.php');
$mssql = new Mssql();
var_dump($mssql->getData("EXEC GetMaxGrossWeightVehicle"));