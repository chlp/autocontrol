<?php

class Logs
{
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    private static function getDb(): Mysql
    {
        require_once(__DIR__ . '/Mysql.php');
        return Mysql::init();
    }

    public static function save(string $resource, string $activity, string $sendedData): Logs
    {
        $db = self::getDb();
        $resource = $db->escape($resource);
        $activity = $db->escape($activity);
        $sendedData = $db->escape(substr($sendedData, 0, 4500));
        $id = $db->insert("INSERT INTO `log_calls` SET `resource`='{$resource}', `activity`='{$activity}', `sended_time`=NOW(), `sended_data`='{$sendedData}';");
        return new Logs($id);
    }

    public function end(string $receivedData): void
    {
        if ($this->id > 0) {
            $db = self::getDb();
            $receivedData = $db->escape(substr($receivedData, 0, 4500));
            $db->query("UPDATE `log_calls` SET `received_time`=NOW(), `received_data`='{$receivedData}' WHERE `id`='{$this->id}';");
        }
    }
}