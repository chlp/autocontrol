<?php

class Mysql
{
    /**
     * @var Mysql
     */
    static private $instance;
    private $db, $user, $pass, $host;
    public $db_link;

    public function __construct()
    {
        require_once(__DIR__ . '/Config.php');
        $this->db = Config::MYSQL_DB;
        $this->user = Config::MYSQL_LOGIN;
        $this->pass = Config::MYSQL_PASSWORD;
        $this->host = Config::MYSQL_HOST;
        $this->connect();
    }

    public static function init(): Mysql
    {
        if (self::$instance === null) {
            self::$instance = new Mysql();
            self::$instance->connect();
        }
        return self::$instance;
    }

    /**
     * Подключение к базе данных
     */
    private function connect()
    {
        $this->db_link = mysqli_connect($this->host, $this->user, $this->pass, $this->db) or $this->error("Невозможно подключиться к MySQL серверу");
        mysqli_set_charset($this->db_link, 'utf8');
    }

    /**
     * Аналог mysqli_query
     * @param string $query запрос к базе
     * @return string Текст ошибки в случаи неудачи
     */
    public function query($query)
    {
        $result = mysqli_query($this->db_link, $query) or $this->error($query . PHP_EOL . mysqli_error($this->db_link));
        return $result;
    }

    /**
     * @param string $str
     * @return string
     */
    public function escape(string $str): string
    {
        return mysqli_real_escape_string($this->db_link, $str);
    }

    /**
     * @param $query
     * @return int
     */
    public function insert($query): int
    {
        $this->query($query);
        return $this->lastInsertId();
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int)mysqli_insert_id($this->db_link);
    }

    public function assoc($query)
    {
        $result = mysqli_query($this->db_link, $query);
        while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
        if (isset($rows)) return $rows;
        else return [];
    }

    public function assoc1($query)
    {
        $result = mysqli_query($this->db_link, $query);
        while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
        if (isset($rows)) return $rows[0];
        else return 0;
    }

    public function getOne($query)
    {
        $result = mysqli_query($this->db_link, $query);
        while ($row = mysqli_fetch_row($result)) $rows[] = $row;
        if (isset($rows)) return $rows[0][0];
        else return 0;
    }

    public function num_rows($query)
    {
        return mysqli_num_rows(mysqli_query($this->db_link, $query));
    }

    private function error($error)
    {

    }
}