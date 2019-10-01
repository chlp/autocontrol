<?php

class PgClass
{
    private $db, $user, $pass, $host;
    public $db_link;

    public function __construct($db, $user, $pass, $host)
    {
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->connect();
    }

    /**
     * Подключение к базе данных
     */
    private function connect()
    {
        $this->db_link = pg_connect("host=" . $this->host . " port=5432 dbname=" . $this->db . " user=" . $this->user . " password=" . $this->pass . " options='--client_encoding=UTF8'");
		pg_set_client_encoding($this->db_link, "UNICODE");
    }

    /**
     * Аналог pg_query
     * @param strig $query запрос к базе
     * @return string Текст ошибки в случаи неудачи
     */
    public function query($query)
    {
        $result = pg_query($query, $this->db_link);
        return $result;
    }

    /**
     * Аналог pg_fetch_assoc()
     * @param resource $result
     * @return Array
     */
    public function fetch_assoc($result)
    {
        return pg_fetch_assoc($result);
    }

    public function assoc($query)
    {
        $result = pg_query($query);
        while ($row = pg_fetch_assoc($result)) $rows[] = $row;
        if (isset($rows)) return $rows;
        else return 0;
    }

    public function assoc1($query)
    {
        $result = pg_query($query);
        while ($row = pg_fetch_assoc($result)) $rows[] = $row;
        if (isset($rows)) return $rows[0];
        else return 0;
    }

    public function getOne($query)
    {
        $result = pg_query($query);
        while ($row = pg_fetch_row($result)) $rows[] = $row;
        if (isset($rows)) return $rows[0][0];
        else return 0;
    }

    public function num_rows($query)
    {
        return pg_num_rows(pg_query($query));
    }

    /**
     * Текст ошибки в печать
     * @param string $text текст ошибки
     */
    protected function error($text)
    {
        echo "<p>" . $text . "</p>";
    }
}
