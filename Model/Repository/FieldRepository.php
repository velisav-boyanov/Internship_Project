<?php

namespace Model\Repository;

class FieldRepository
{
   public function saveField($fieldToInsert)
   {
       $pdo = DBManager::getInstance()->getConnection();

       $sql = 'INSERT INTO `Field` (`Width`, `Length`, `Bomb_Intensity`, `End_X`, `End_Y`)
               VALUES (:Width, :Length, :Bomb_Intensity, :End_X, :End_Y)';

       $stmt = $pdo->prepare($sql);
       $stmt->execute($fieldToInsert);
       return $pdo->lastInsertId();
   }

   public function getFieldById($fieldId)
   {
       $pdo = DBManager::getInstance()->getConnection();

       $sql = 'SELECT * FROM `Field` WHERE `Field_Id` = :fieldId';

       $stmt = $pdo->prepare($sql);
       $stmt->execute(['fieldId' => $fieldId,]);

       $result = $stmt->fetch(\PDO::FETCH_ASSOC);
       return $result;
   }

   public function getAllFields(){
       $pdo = DBManager::getInstance()->getConnection();

       $sql = 'SELECT * FROM `Field`';

       $stmt = $pdo->prepare($sql);
       $stmt->execute();

       $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
       return $result;
   }
    public function getFieldId($End_X, $End_Y, $Width, $Length, $Bomb_Intensity){
        $pdo = DBManager::getInstance()->getConnection();

        $sql = 'SELECT Field_Id FROM `Field` 
                WHERE `End_X` = :End_X 
                AND `End_Y` = :End_Y 
                AND `Width` = :Width 
                AND `Length` = :Length 
                AND `Bomb_Intensity` = :Bomb_Intensity';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['End_X' => $End_X, 'End_Y' => $End_Y, 'Width' => $Width, 'Length' => $Length, 'Bomb_Intensity' => $Bomb_Intensity,]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
}