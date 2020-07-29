<?php

namespace Model\Repository;


class ItemRepository
{
    public function saveItem($itemToInsert)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'INSERT INTO `Item` (`Name`, `Player_id`, `Field_Id`)
               VALUES (:Name, :Player_Id, :Field_Id)';

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($itemToInsert);
    }

    public function getItemById($itemId)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Item` WHERE `Item_Id` = :Item_Id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['itemId' => $itemId,]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllItems($player)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT 
                    * 
                FROM 
                    `Item` 
                WHERE 
                    `Player_Id` = :player';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['player' => $player]);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getSlotByFieldAndPlayerId($field, $player, $name)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT 
                    * 
                FROM 
                    `Item` 
                WHERE 
                    `Field_Id` = :field 
                    AND `Player_Id` = :player 
                    AND `Name` = :name';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['field' => $field,
                        'player' => $player,
                        'name' => $name]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function useItem($field, $player, $name)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'DELETE FROM 
                    `Item` 
                WHERE 
                    `Field_Id` = :field 
                    AND `Player_Id` = :player 
                    AND `Name` = :name LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['field' => $field,
                        'player' => $player,
                        'name' => $name]);
    }

    public function removeItems($id)
    {
        $pdo = DBManager::getInstance()->getConnection();
        $sql = 'DELETE FROM 
                    `Item` 
                WHERE 
                    `Field_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    public function getNumberOfItem($name, $player) {
        $pdo = DBManager::getInstance()->getConnection();
        $sql = 'SELECT 
                    COUNT(`Item_Id`) 
                FROM 
                    `Item` 
                WHERE 
                    `Player_Id` = :player 
                    AND `Name` = :name';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'player' => $player]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
}