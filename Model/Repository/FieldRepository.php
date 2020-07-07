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
       return $stmt->execute($fieldToInsert);
   }

   public function getFieldById($fieldId)
   {
       $pdo = DBManager::getInstance()->getConnection();

       $sql = 'SELECT * FROM `Field` WHERE `Field_Id` = :bookId';

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

       $result = $stmt->fetchAll(\POD::FETXH_ASSOC);
       return $result;
   }
}