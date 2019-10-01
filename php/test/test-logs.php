<?php
require_once(__DIR__ . '/../Logs.php');
$l = Logs::save('test_r', 'act', json_encode(['a' => 12]));
$l->end(json_encode(['b' => 25]));