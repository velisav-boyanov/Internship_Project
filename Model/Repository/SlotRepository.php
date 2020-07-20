<?php

namespace Model\Repository;

class SlotRepository
{
    public function saveSlot($SlotToInsert)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'INSERT INTO `Slot` (`X`, `Y`, `Field_Id`, `Damage`)
               VALUES (:X, :Y, :Field_Id, :Damage)';

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($SlotToInsert);
    }

    public function getSlotById($slotId)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Slot` WHERE `Slot_Id` = :slotid';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['slotid' => $slotId]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllSlots(){
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Slot`';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDamageByFieldXY($fieldId, $x, $y)
    {
        $id = $fieldId;

        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Slot` WHERE `Field_Id` = :id AND `X` = :x AND `Y` = :y';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['x' => $x, 'y' => $y, 'id' => $id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function find($slotId)
    {
        $id = $slotId;
        $found = 1;

        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE `Slot` SET `Found` = :found WHERE `Slot`.`Slot_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['found' => $found, 'id' => $id]);
    }

    public function emptyBomb($slotId)
    {
        $id = $slotId;
        $damage = 0;

        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE `Slot` SET `Damage` = :damage WHERE `Slot`.`Slot_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['damage' => $damage, 'id' => $id]);

    }
    public function removeSlots($fieldId)
    {
        $id = $fieldId;

        $pdo = DBManager::getInstance()->getConnection();
        $sql = 'DELETE FROM `Slot` WHERE `Slot` . `Field_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function setRadar($radar, $slotId){
        $id = $slotId;

        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE `Slot` SET `Radar` = :radar WHERE `Slot`.`Slot_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['radar' => $radar, 'id' => $id]);
    }
}