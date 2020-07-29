<?php

namespace Model\Services;

use Model\Repository\FieldRepository;

class FieldService
{
    public function saveField($width, $length, $bombIntensity, $endX, $endY)
    {
        $result = ['success' => false];

        $repo = new FieldRepository();

        $fieldToInsert = [
            'Width' => $width,
            'Length' => $length,
            'Bomb_Intensity' => $bombIntensity,
            'End_X' => $endX,
            'End_Y' => $endY
        ];
        if($fieldId = $repo->saveField($fieldToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Field successfully added!';
        }
        //COOKIE
        $cookieName = 'MyFieldId';
        $date = time() + (60*60*24*7*2);
        setcookie($cookieName, $fieldId, $date);

        return $result;
    }

    public function getField($fieldId)
    {
        $result = [
            'success' => false
        ];

        $repo = new FieldRepository();
        $field = $repo->getFieldById($fieldId);

        if (!$field) {
            $result['msg'] = 'Field with id ' . $fieldId . ' was not found!';
            return $result;
        }

        $result['success'] = true;
        $result['field'] = $field;
        return $result;
    }

    public function getAllFields()
    {
        $result = [
            'success' => false
        ];

        $repo = new FieldRepository();
        $field = $repo->getAllFields();

        if (!$field) {
            $result['msg'] = 'There are no fields yet!';
            return $result;
        }

        $result['success'] = true;
        $result['field'] = $field;
        return $result;
    }
}