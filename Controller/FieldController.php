<?php

namespace Controller;

USE Model\Services\FieldService;
USE Core\View;

class FieldController
{
    public function add()
    {
        $result = [
            'success' => false
        ];

        $Width = $_POST['Width'] ?? '';
        $Length = $_POST['Length'] ?? '';
        $Bomb_Intensity = $_POST['Bomb_Intensity'] ?? '';
        $End_X = $_POST['End_X'] ?? '';
        $End_Y = $_POST['End_Y'] ?? '';

        if(
            !$this->validateSize($Length)
            || !$this->validateSize($Width)
            || !$this->validatePosition($End_Y, $Length)
            || !$this->validatePosition($End_X, $Width)
            || !$this->validateFloat($Bomb_Intensity)
        )
        {
            $result['msg'] = 'Invalid field parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new FieldService();
        $result = $service->saveField($Width, $Length, $Bomb_Intensity, $End_X, $End_Y);

        echo json_encode($result, JSON_PRETTY_PRINT);

        View::render('field');
    }

    public function getById($fieldId)
    {
        $result = [
            'success' => false
        ];

        //$fieldId = $_POST['fieldId'] ?? '0';

        if (!$this->validateNumber($fieldId)) {
            $result['msg'] = 'Invalid field id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new FieldService();
        $result = $service->getField($fieldId);

        echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
    }

    public function getAll()
    {
        $service = new FieldService();
        $result = $service->getAllFields();

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateNumber($number){
        return $number > 0;
    }

    private function validateSize($size){
        return ($size > 0 && $size <= 8);
    }

    private function validatePosition($number, $size){
        return $number <= $size;
    }

    private function validateFloat($float){
        return ($float > 0 && $float < 1);
    }
}