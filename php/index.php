<?php

error_reporting(NULL);
date_default_timezone_set('Europe/Moscow');

function shutdown()
{
    $error = error_get_last();
    if ($error['type'] === E_ERROR) {
        file_put_contents(__DIR__ . '/errors/' . time() . '.txt', json_encode($error));
    }
}

register_shutdown_function('shutdown');

$formErrors = array();
$formSuccess = array();

session_start();

require_once(__DIR__ . '/Config.php');

require_once('Mysql.php');
$db = Mysql::init();

require_once('Mssql.php');
$mssql = new Mssql();

require_once(__DIR__ . '/include/NavisionWsdl/autoload.php');
$navision = new nav\DepartureCar1(
    Config::NAVISION_LOGIN,
    Config::NAVISION_PASSWORD,
    $db->getOne("SELECT `value` FROM `settings` WHERE `name` = 'navision'"),
    __DIR__ . '/navision.wsdl'
);

require_once('extFunctions.php');
require_once('userClass.php');
require_once('Terminal.php');
require_once('PlateRecognizer.php');

if ($_GET['page'] === 'updateWarehouseTerminal') {
    require_once('warehouse.php');
    exit();
}

$ajax = ($_REQUEST['ajax'] ?? false) || in_array($_GET['page'], ['weight', 'plate', 'relay']);

$user = new userClass($db);
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $key = $_SESSION['key'];
} else if (isset($_COOKIE['user'])) {
    $login = $_COOKIE['login'];
    $key = $_COOKIE['key'];
} else {
    include('login.php');
    exit();
}

if ($user->authByKey($login, $key) == 0) {
    include('login.php');
    exit();
}

if (!isset($_SESSION['terminal'])) {
    header('Location: /logout');
    exit;
}

$terminal = new Terminal($_SESSION['terminal'], $db);
if ($terminal->id === 0) {
    header('Location: /logout');
    exit;
}
$gateIn = $terminal->gateIn;
$gateOut = $terminal->gateOut;
$stateIn = $terminal->stateIn;
$stateOut = $terminal->stateOut;
$normDirection = $terminal->normDirection;

require_once('relayClass.php');
$relay = new RelayClass($terminal->relayIp, 1, $db);

if (!isset($_SESSION['login'])) {
    $_SESSION['login'] = $_COOKIE['login'];
    $_SESSION['key'] = $_COOKIE['key'];
}

if ($_GET['page'] === 'logout') {
    $title = 'Выход';
    require_once('login.php');
}

if (session_status() !== PHP_SESSION_NONE) {
    session_write_close();
}
require_once('warehouse.php');

// Сюда попадают только авторизованные
switch ($_GET['page']) {
    case 'realTime':
        require_once('realTime.php');
        exit();
        break;
    case 'relay':
        require_once('getRelay.php');
        exit();
        break;
    case 'weight':
        echo getWeight($terminal->ip);
        exit();
        break;
    case 'plate':
        $visit = $db->assoc1("SELECT `dateStart`, `direction`, `close` FROM `visits` WHERE `terminal`='" . $terminal->id . "' AND `close` = '0' LIMIT 1;");
        if (isset($visit['direction'])) {
            $terminal->setCurrentDirection((int)$visit['direction']);
        }
        $startDate = 0;
        if ($visit['dateStart']) {
            $startDate = (int)$visit['dateStart'];
        }
        echo (new PlateRecognizer())->getCurrentPlate($terminal->getCurrentDirection(), $terminal->getCurrentPlateRecChannel(), $startDate);
        exit();
        break;
    case 'ttnScan':
        if (!isset($_POST['ttn'])) {
            echo 'Не передан ТТН';
        } else {
            $_POST['ttn'] = trim($_POST['ttn']);
            $ttn = $db->escape($_POST['ttn']);
            if ($ttn !== $_POST['ttn']) {
                echo 'Неверно передан ТТН';
            } else {
                $num = $db->num_rows("SELECT * FROM `currentTtns` WHERE `terminal` = '" . $terminal->id . "' AND `ttn` = '" . $ttn . "';");
                if ($num > 0) {
                    echo 'Данный ТТН уже отсканирован';
                } else {
                    $visit = $db->query("INSERT INTO `currentTtns` (`terminal`, `ttn`, `ttnAbout`) VALUES ('" . $terminal->id . "', '" . $ttn . "', '');");
                    echo 'ok';
                }
            }
        }
        exit();
        break;
}

if (!isset($ajax) || !$ajax) {
    require_once('header.php');
}

require_once('logic.php');

if (!isset($ajax) || !$ajax) {
    require_once('footer.php');
}