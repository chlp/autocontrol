<?php

class PlateRecognizer
{
    public function __construct()
    {
        require_once('PgClass.php');
        require_once(__DIR__ . '/Config.php');
        $this->db = new PgClass(Config::PLATERECOGNIZER_DB, Config::PLATERECOGNIZER_LOGIN, Config::PLATERECOGNIZER_PASSWORD, Config::PLATERECOGNIZER_HOST);
    }

    private $db;

    private function currentTablesName(int $direction): array
    {
        if ($direction === Terminal::IN) {
            $tName = 'in';
        } else {
            $tName = 'out';
        }
        return array_map(static function ($element) use ($tName) {
            return $tName . (string)$element['trstime'];
        }, $this->db->assoc('SELECT trstime FROM "' . $tName . '" ORDER BY trstime DESC LIMIT 3;'));
    }

    public function getCurrentPlate(int $direction, int $channel, int $startDate): string
    {
        $fromTime = date('YmdHis', $startDate);
        $tablesName = $this->currentTablesName($direction);
        $sql = "
            SELECT trplate FROM
            (
                (
                    SELECT trplate, trsystime, trvalid
                    FROM \"{$tablesName[0]}\"
                    WHERE trchannel = '{$channel}' AND trsystime > '{$fromTime}' 
                    ORDER BY trvalid DESC, trsystime DESC
                    LIMIT 1
                ) UNION (
                    SELECT trplate, trsystime, trvalid
                    FROM \"{$tablesName[1]}\"
                    WHERE trchannel = '{$channel}' AND trsystime > '{$fromTime}'
                    ORDER BY trvalid DESC, trsystime DESC
                    LIMIT 1
                ) UNION (
                    SELECT trplate, trsystime, trvalid
                    FROM \"{$tablesName[2]}\"
                    WHERE trchannel = '{$channel}' AND trsystime > '{$fromTime}'
                    ORDER BY trvalid DESC, trsystime DESC
                    LIMIT 1
                )
            ) tmp
            ORDER BY trvalid DESC, trsystime DESC
            LIMIT 1
        ";
        $plate = $this->db->getOne($sql);

        if ($plate === 0) {
            return '';
        }
        return (string)normalizeNumber((string)$plate);
    }
}