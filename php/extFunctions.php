<?php

function myGetDate($type = '1', $time = 0)
{
    if ($time == 0) {
        $time = time();
    }
    
    $months = array(0, 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

    switch ($type) {
        case '1':
            return date("j " . $months[date('n', $time)] . " Y H:i", $time);
            break;
        case 'time':
            return date("G:i", $time);
            break;
        case 'date':
            return date("j.n.Y", $time);
            break;
        case '4xls':
            return date("j.n.Y H:i", $time);
            break;
        case 'textDate':
            return date("j " . $months[date('n', $time)] . " Y", $time);
            break;
        case 'textDateTime':
            return date("j " . $months[date('n', $time)] . " Y H:i", $time);
            break;
        default:
            return 0;
            break;
    }
}

function getWeight($ip)
{
    global $formErrors, $db;

    $weightStatus = $db->assoc1("SELECT `value`, `date` FROM `weightStatus` WHERE `ip` = '" . $ip . "' LIMIT 1;");
    if ($weightStatus != 0) {
        if (time() - $weightStatus['date'] <= 1) {
            return (int)$weightStatus['value'];
        }
    }

    $ctx = stream_context_create(array('http' =>
        array(
            'timeout' => 2
        )
    ));
    $return = file_get_contents('http://' . $ip . ':8844/', false, $ctx);

    if (is_bool($return) && (!$return)) // <- Ошибка связи с весами
    {
        return "connection_error\nНет связи с весами (" . $ip . ")";
    }
    $newValue = (int)file_get_contents('http://' . $ip . ':8844/', false, $ctx);
    if ($weightStatus != 0) // запись для этих весов уже существует - обновим
    {
        $db->query("UPDATE `weightStatus` SET `value` = '" . $newValue . "', `date` = '" . time() . "' WHERE `ip` = '" . $ip . "' LIMIT 1;");
    } else { // записи для этих весов еще нет - вставим
        $db->query("INSERT INTO `weightStatus` (`ip`, `value`, `date`) VALUES ('" . $ip . "', '" . $newValue . "', '" . time() . "');");
    }
    return (int)$newValue;
}

function getRelayStatusString(RelayClass $relay, Terminal $terminal)
{
    $str = ($relay->read($terminal->stateIn) ? "1, " : "0, ");
    $str .= ($relay->read($terminal->stateOut) ? "1, " : "0, ");
    $str .= ($relay->read($terminal->gateIn) ? "1, " : "0, ");
    $str .= ($relay->read($terminal->gateOut) ? "1" : "0");
    return $str;
}

function normalizeNumber($number)
{
    $search = array('Е', 'Т', 'У', 'О', 'Р', 'А', 'Д', 'Н', 'К', 'Х', 'С', 'В', 'И', 'М', ' ');
    $search_ = array('е', 'т', 'у', 'о', 'р', 'а', 'д', 'н', 'к', 'х', 'с', 'в', 'и', 'м', ' ');
    $searchL_ = array('e', 't', 'y', 'o', 'p', 'a', 'd', 'h', 'k', 'x', 'c', 'b', 'n', 'm', ' ');
    $replace = array('E', 'T', 'Y', 'O', 'P', 'A', 'D', 'H', 'K', 'X', 'C', 'B', 'N', 'M', '');
    $number = str_replace($search, $replace, $number);
    $number = str_replace($search_, $replace, $number);
    $number = str_replace($searchL_, $replace, $number);

    $number = preg_replace("/[^a-z0-9\s]/ui", "", $number);

    return $number;
}

function ping($ip)
{
    exec("ping -n 1 -w 500 $ip", $output, $status);
    return (int)$status === 0;
}