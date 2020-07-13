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

        $sql = 'SELECT * FROM `Player` WHERE `Player_Id` = :playerId';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['playerId' => $playerId]);

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
    /////////////////////////////////////////////////////////////////////////////////
    public function move($pos, $axis, $player){
        $pdo = DBManager::getInstance()->getConnection();

        if($axis == 'Y') {
            $sql = 'UPDATE `Player` SET `Y` = :pos WHERE `Player`.`Player_id` = :player';
        }
        if($axis == 'X') {
            $sql = 'UPDATE `Player` SET `X` = :pos WHERE `Player`.`Player_id` = :player';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pos' => $pos, 'player' => $player]);

    }
}