<?php

namespace Controller;

USE Model\Services\FieldService;
USE Core\View;

class FieldController
{
    const MAX_SIZE_FOR_FIELD = 8;
    const MIN_SIZE = 0;
    const MAX_BOMB_INTENSITY = 1;

    public function add()
    {
        $result = [
            'success' => false
        ];

        $width = $_POST['Width'] ?? '';
        $length = $_POST['Length'] ?? '';
        $bombIntensity = $_POST['Bomb_Intensity'] ?? '';
        $endX = $_POST['End_X'] ?? '';
        $endY = $_POST['End_Y'] ?? '';

        if(
            !$this->validateSize($length)
            || !$this->validateSize($width)
            || !$this->validatePosition($endY, $length)
            || !$this->validatePosition($endX, $width)
            || !$this->validateBombIntensity($bombIntensity)
        )
        {
            $result['msg'] = 'Invalid field parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new FieldService();
        $result = $service->saveField($width, $length, $bombIntensity, $endX, $endY);

        //echo json_encode($result, JSON_PRETTY_PRINT);

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

        //echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
    }

    public function getAll()
    {
        $service = new FieldService();
        $result = $service->getAllFields();

        //echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateNumber($number){
        return $number > 0;
    }

    private function validateSize($size){
        return ($size > self::MIN_SIZE && $size <= self::MAX_SIZE_FOR_FIELD);
    }

    private function validatePosition($number, $size){
        return $number <= $size;
    }

    private function validateBombIntensity($bombIntensity){
        return ($bombIntensity > self::MIN_SIZE && $bombIntensity < self::MAX_BOMB_INTENSITY);
    }
}