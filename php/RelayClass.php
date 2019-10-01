<?php

class RelayClass
{
    private $isOk;

    public function __construct($ip, $unitId, $db)
    {
        require_once(__DIR__ . '/include/Phpmodbus/ModbusMaster.php');
        require_once(__DIR__ . '/Config.php');
        $this->_unitId = $unitId;
        $this->_ip = $ip;
        $this->_modbus = new ModbusMaster($ip, Config::RELAY_PROTOCOL);
        $this->_db = $db;
    }

    private $_unitId;
    private $_ip;
    private $_modbus;
    private $_db;

    private function updateRegisterStatus($register, $registerStatus, $newValue)
    {
        if (!$this->checkIsOk()) {
            return;
        }
        if ($registerStatus != 0) // запись для этого регистра уже существует - обновим
        {
            $this->_db->query("UPDATE `relayStatus` SET `value` = '" . $newValue . "', `date` = '" . time() . "' WHERE `register` = '" . $register . "' AND `ip` = '" . $this->_ip . "' LIMIT 1;");
        } else // записи для данного регистра еще нет - вставим
        {
            $this->_db->query("INSERT INTO `relayStatus` (`ip`, `register`, `value`, `date`) VALUES ('" . $this->_ip . "', '" . $register . "', '" . $newValue . "', '" . time() . "');");
        }
    }

    public function read($register, $retry = false)
    {
        if (!$this->checkIsOk()) {
            return 0;
        }
        $registerStatus = $this->_db->assoc1("SELECT `value`, `date` FROM `relayStatus` WHERE `register` = '" . $register . "' AND `ip` = '" . $this->_ip . "' LIMIT 1;");
        if ((!$retry) && ($registerStatus != 0)) {
            if (time() - $registerStatus['date'] <= 2) {
                return $registerStatus['value'];
            }
        }
        try {
            $recData = $this->_modbus->readCoils($this->_unitId, $register, 1);
            $this->updateRegisterStatus($register, $registerStatus, $recData[0]);
            return $recData[0];
        } catch (Exception $e) {
            $this->_ip = '127.0.0.1';
            $formErrors[] = 'Ошибка подключения к релейной плате. Проверьте питание и соединение. IP ' . $this->_ip;
            return 0;
        }
    }

    public function write($register, $value)
    {
        if (!$this->checkIsOk()) {
            return 0;
        }
        $data = [$value];
        try {
            $this->_modbus->writeMultipleCoils($this->_unitId, $register, $data);
            $registerStatus = $this->_db->assoc1("SELECT `value`, `date` FROM `relayStatus` WHERE `register` = '" . $register . "' AND `ip` = '" . $this->_ip . "' LIMIT 1;");
            $this->updateRegisterStatus($register, $registerStatus, $value);
        } catch (Exception $e) {
            $formErrors[] = 'Ошибка подключения к релейной плате. Проверьте питание и соединение. IP ' . $this->_ip;
            return 0;
        }
        return 1;
    }

    private function checkIsOk()
    {
        if ($this->isOk === null) {
            $this->isOk = ping($this->_ip);
        }
        return $this->isOk;
    }

    public function error()
    {
        return !$this->isOk;
    }
}
