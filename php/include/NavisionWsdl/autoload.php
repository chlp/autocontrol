<?php


 function autoload_9ebfa1b924ebd15bbcb86df3a3abba40($class)
{
    $classes = array(
        'nav\NavError' => __DIR__ .'/NavError.php',
        'nav\DepartureCar1' => __DIR__ .'/DepartureCar1.php',
        'nav\CheckDepatureCar' => __DIR__ .'/CheckDepatureCar.php',
        'nav\CheckDepatureCar_Result' => __DIR__ .'/CheckDepatureCar_Result.php',
        'nav\CheckSLCar' => __DIR__ .'/CheckSLCar.php',
        'nav\CheckSLCar_Result' => __DIR__ .'/CheckSLCar_Result.php',
        'nav\CheckSealNumber' => __DIR__ .'/CheckSealNumber.php',
        'nav\CheckSealNumber_Result' => __DIR__ .'/CheckSealNumber_Result.php',
        'nav\CheckDepatureCar1' => __DIR__ .'/CheckDepatureCar1.php',
        'nav\CheckDepatureCar1_Result' => __DIR__ .'/CheckDepatureCar1_Result.php',
        'nav\CheckArrivalCar1' => __DIR__ .'/CheckArrivalCar1.php',
        'nav\CheckArrivalCar1_Result' => __DIR__ .'/CheckArrivalCar1_Result.php',
        'nav\CheckSLCar1' => __DIR__ .'/CheckSLCar1.php',
        'nav\CheckSLCar1_Result' => __DIR__ .'/CheckSLCar1_Result.php',
        'nav\CheckDepatureCar1_test' => __DIR__ .'/CheckDepatureCar1_test.php',
        'nav\CheckDepatureCar1_test_Result' => __DIR__ .'/CheckDepatureCar1_test_Result.php',
        'nav\GetWeightLimit' => __DIR__ .'/GetWeightLimit.php',
        'nav\GetWeightLimit_Result' => __DIR__ .'/GetWeightLimit_Result.php',
        'nav\GetLoadPlanWeightByAShNumber' => __DIR__ .'/GetLoadPlanWeightByAShNumber.php',
        'nav\GetLoadPlanWeightByAShNumber_Result' => __DIR__ .'/GetLoadPlanWeightByAShNumber_Result.php',
        'nav\CheckSLCar2' => __DIR__ .'/CheckSLCar2.php',
        'nav\CheckSLCar2_Result' => __DIR__ .'/CheckSLCar2_Result.php',
        'nav\CheckArrivalCar2' => __DIR__ .'/CheckArrivalCar2.php',
        'nav\CheckArrivalCar2_Result' => __DIR__ .'/CheckArrivalCar2_Result.php',
        'nav\CheckDepatureCar2' => __DIR__ .'/CheckDepatureCar2.php',
        'nav\CheckDepatureCar2_Result' => __DIR__ .'/CheckDepatureCar2_Result.php',
        'nav\CheckDepartureCargoOlymp' => __DIR__ .'/CheckDepartureCargoOlymp.php',
        'nav\CheckDepartureCargoOlymp_Result' => __DIR__ .'/CheckDepartureCargoOlymp_Result.php',
        'nav\ArrivalCargoOlymp' => __DIR__ .'/ArrivalCargoOlymp.php',
        'nav\ArrivalCargoOlymp_Result' => __DIR__ .'/ArrivalCargoOlymp_Result.php',
        'nav\OpenStageBeforeKPP' => __DIR__ .'/OpenStageBeforeKPP.php',
        'nav\OpenStageBeforeKPP_Result' => __DIR__ .'/OpenStageBeforeKPP_Result.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_9ebfa1b924ebd15bbcb86df3a3abba40');

// Do nothing. The rest is just leftovers from the code generation.
{
}
