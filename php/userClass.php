<?php

class userClass
{
    private const SALT = 'atctrl';

    public function __construct($db)
    {
        $this->id = 0;
        $this->_role = 0;
        $this->_login = 0;
        $this->_password = 0;
        $this->db = $db;
    }

    private $_role, $_login, $_password, $db;
    public $id;

    public function authByKey($login, $key)
    {
        $user = $this->getUser((string)$login);
        if ($user == 0)
            return 0;

        $realKey = md5($user['login'] . self::SALT . $user['password']);

        if ($key != $realKey)
            return 0;
        else {
            $this->id = $user['id'];
            $this->_role = $user['role'];
            $this->_login = $user['login'];
            $this->_password = $user['realPassword'];
            return 1;
        }
    }

    public function authByPass($login, $password, &$navision, &$formErrors)
    {
        $method = new nav\CheckSLCar2($login, $password, '', '0', '0', '', '', 0, '');
        $response = $navision->CheckSLCar2($method);

        if ($response->getErrorText() !== 'Нет сопроводительного листа с таким номером.') {
            $formErrors[] = $response->getErrorText();
            return 0;
        }
        if (!$this->getUser((string)$login, (string)$password)) {
            $user_ = $this->getUser((string)$login);
            if ($user_) {
                $this->db->query("UPDATE `users` SET `password`='" . md5($password) . "', `realPassword`='" . $password . "' WHERE `id`='" . $user_['id'] . "'");
            } else {
                $this->db->query("INSERT INTO `users` (`login`, `password`, `realPassword`, `role`) VALUES ('" . $login . "', '" . md5($password) . "', '" . $password . "', '1')");
            }
        }

        $this->_password = $password;
        return md5($login . self::SALT . md5($password));
    }

    public function getUser($var, $var2 = 0)
    {
        if (is_int($var2) && $var2 == 0) {
            if (is_int($var))
                return $this->db->assoc1("SELECT * FROM `users` WHERE `id`='" . $var . "' LIMIT 1;");
            else if (is_string($var))
                return $this->db->assoc1("SELECT * FROM `users` WHERE `login`='" . $var . "' LIMIT 1;");
        } else if (is_string($var) && is_string($var2)) {
            return $this->db->assoc1("SELECT * FROM `users` WHERE `login`='" . $var . "' AND `password`='" . md5($var2) . "' LIMIT 1;");
        }
        return 0;
    }

    public function realPassword()
    {
        return $this->_password;
    }

    public function login()
    {
        return $this->_login;
    }
}
