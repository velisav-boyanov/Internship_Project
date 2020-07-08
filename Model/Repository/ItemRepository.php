<?php

namespace Model\Repository;


class ItemRepository
{
    public function saveItem($ItemToInsert)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'INSERT INTO `Item` (`Name`, `Player_id`, `Field_Id`)
               VALUES (:Name, :Player_Id, :Field_Id)';

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($ItemToInsert);
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

    public function getAllItems(){
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Item`';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}