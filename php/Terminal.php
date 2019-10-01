<?php

class Terminal
{
    public const IN = 1;
    public const OUT = 0;

    public $relayIp;

    public function __construct($id, $db)
    {
        $this->db = $db;
        $terminalFull = $this->db->assoc1("SELECT * FROM `terminals` WHERE `id`='" . $id . "';");
        $this->id = (int)($terminalFull['id'] ?? 0);
        if ($this->id === 0) {
            return;
        }
        $this->ip = $terminalFull['ip'];
        $this->relayIp = $terminalFull['relayIp'];
        $this->gateIn = $terminalFull['gateIn'];
        $this->gateOut = $terminalFull['gateOut'];
        $this->stateIn = $terminalFull['stateIn'];
        $this->stateOut = $terminalFull['stateOut'];
        $this->autoWeit = $terminalFull['autoWeit'] ?? 0;
        $this->itemWeit = $terminalFull['itemWeit'] ?? 0;
        $this->seal = $terminalFull['seal'] ?? '';
        $this->normDirection = $terminalFull['normDirection'];
        $this->setCurrentDirection((int)$this->normDirection);
        $this->plateRecIn = $terminalFull['plateRecIn'];
        $this->plateRecOut = $terminalFull['plateRecOut'];
        $this->name = $terminalFull['name'];
    }

    public $id, $ip, $gateIn, $gateOut, $stateIn, $stateOut, $normDirection, $autoWeit, $itemWeit, $seal, $planWeight, $autoType;
    private $db, $currentDirection, $plateRecIn, $plateRecOut, $name;

    public function setCurrentDirection(int $direction): void
    {
        $this->currentDirection = $direction;
    }

    public function getCurrentDirection(): int
    {
        return (int)$this->currentDirection;
    }

    public function getName(): string
    {
        return (string)$this->name;
    }

    public function currentRelay($v)
    {
        $noReverse = $this->getCurrentDirection() === self::IN;
        switch ($v) {
            case 'gateIn':
                return ($noReverse) ? $this->gateIn : $this->gateOut;
                break;
            case 'gateOut':
                return ($noReverse) ? $this->gateOut : $this->gateIn;
                break;
            case 'stateIn':
                return ($noReverse) ? $this->stateIn : $this->stateOut;
                break;
            case 'stateOut':
                return ($noReverse) ? $this->stateOut : $this->stateIn;
                break;
        }
        return 0;
    }

    public function getCurrentPlateRecChannel(): int
    {
        if ($this->getCurrentDirection() === self::IN) {
            return (int)$this->plateRecIn;
        }
        return (int)$this->plateRecOut;
    }

    public function current($v)
    {
        $noReverse = $this->getCurrentDirection() === self::IN;
        switch ($v) {
            case 'gateIn':
                return ($noReverse) ? 'gateIn' : 'gateOut';
                break;
            case 'gateOut':
                return ($noReverse) ? 'gateOut' : 'gateIn';
                break;
            case 'stateIn':
                return ($noReverse) ? 'stateIn' : 'stateOut';
                break;
            case 'stateOut':
                return ($noReverse) ? 'stateOut' : 'stateIn';
                break;
        }
        return 0;
    }

    public function saveSeal($seal)
    {
        $seal = (string)$seal;

        if (strlen($seal) > 240) {
            $seal = substr($seal, 0, 240);
        }

        $this->db->query("UPDATE `visits` SET `seal`='" . $seal . "' WHERE `terminal`='" . $this->id . "' LIMIT 1;");
        $this->seal = $seal;
        return true;
    }

    public function saveWeits($autoWeit, $itemWeit)
    {
        $autoWeit = (int)$autoWeit;
        $itemWeit = (int)$itemWeit;
        $this->db->query("UPDATE `visits` SET `autoWeit`='" . $autoWeit . "', `itemWeit`='" . $itemWeit . "' WHERE `terminal`='" . $this->id . "' LIMIT 1;");
        $this->autoWeit = $autoWeit;
        $this->itemWeit = $itemWeit;
        return true;
    }
}
