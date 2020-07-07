<?php

namespace Model\Services;

use Model\Repository\FieldRepository;

class FieldService
{
    public function saveField($Width, $Length, $Bomb_Intensity, $End_X, $End_Y)
    {
        $result = ['success' => false];

        $repo = new FieldRepository();

        $fieldToInsert = [
            'Width' => $Width,
            'Length' => $Length,
            'Bomb_Intensity' => $Bomb_Intensity,
            'End_X' => $End_X,
            'End_Y' => $End_Y
        ];

        if($repo->saveField($fieldToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Field successfully added!';
        }
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