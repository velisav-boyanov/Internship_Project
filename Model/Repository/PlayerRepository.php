<?php

namespace Model\Repository;

class PlayerRepository
{
    public function savePlayer($PlayerToInsert)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'INSERT INTO `Player` (`X`, `Y`, `Field_Id`, `Health`)
               VALUES (:X, :Y, :Field_Id, :Health)';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($PlayerToInsert);
        return $pdo->lastInsertId();
    }

    public function getPlayerById($playerId)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Player` WHERE `Player_Id` = :Player_Id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['playerId' => $playerId,]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllPlayers(){
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Player`';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

}