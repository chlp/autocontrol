<?php

class Mssql
{
    protected $connection = null;

    public function __construct()
    {
        $this->connect();
    }

    public function connect($database = 'MBC2009'): string
    {
        if ($this->connection) {
            return '';
        }
        $mssql_server = 'SRV-MBC-SQL-01';
        $mssql_data = array(
            'Database' => $database,
            'CharacterSet' => 'UTF-8',
            'TrustServerCertificate' => 1,
        );
        $this->connection = sqlsrv_connect($mssql_server, $mssql_data);
        if (!$this->connection) {
            return 'Failed to connect to host';
        }
        return '';
    }

    public function getData($query): array
    {
        $dataArray = [];
        $result = $this->query($query);
        while ($row = sqlsrv_fetch_array($result)) {
            $dataArray[] = $row;
        }
        return $dataArray;
    }

    public function query($query)
    {
        $result = sqlsrv_query($this->connection, $query) or die("Query didn't work..");
        return $result;
    }
}
