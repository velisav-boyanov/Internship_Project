<?php

namespace Model\Repository;

use Controller\PlayerController;

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

        $sql = 'SELECT * FROM `Player` 
                WHERE `Player_Id` = :playerId';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['playerId' => $playerId]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllPlayers()
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT * FROM `Player`';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function move($pos, $axis, $player)
    {
        $pdo = DBManager::getInstance()->getConnection();

        if($axis == PlayerController::Y_AXIS) {
            $sql = 'UPDATE 
                        `Player` 
                    SET 
                        `Y` = :pos 
                    WHERE 
                        `Player_id` = :player';
        }
        if($axis == PlayerController::X_AXIS) {
            $sql = 'UPDATE 
                        `Player` 
                    SET 
                        `X` = :pos 
                    WHERE 
                        `Player_id` = :player';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pos' => $pos, 'player' => $player]);

    }

    public function endGame($player)
    {
        $win = 1;
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE 
                    `Player` 
                SET 
                    `Finished` = :win 
                WHERE 
                    `Player_id` = :player';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['win' => $win, 'player' => $player]);
    }

    public function applyDamage($player, $life)
    {
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE 
                    `Player` 
                SET 
                    `Health` = :life 
                WHERE 
                    `Player_id` = :player';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['life' => $life, 'player' => $player]);
    }

    public function alterCoins($num, $id){
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'UPDATE 
                    `Player`
                SET 
                    `Coins` = :num
                WHERE 
                    `Player_Id` = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['num' => $num, 'id' => $id]);
    }
}