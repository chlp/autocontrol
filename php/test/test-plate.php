<?php

require_once(__DIR__ . '/../extFunctions.php');
require_once(__DIR__ . '/../Terminal.php');
require_once(__DIR__ . '/../PlateRecognizer.php');
require_once(__DIR__ . '/../Config.php');

for ($channel = 0; $channel < 10; ++$channel) {
    foreach ([Terminal::IN, Terminal::OUT] as $direction) {
        echo "Канал: {$channel}-{$direction}: ";
        echo (new PlateRecognizer())->getCurrentPlate($direction, $channel, 0);
        echo "<br>";
    }
}