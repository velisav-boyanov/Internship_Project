<?php


namespace Controller;
use Core\View;
use Model\Services\SlotService;

class SlotController
{
    public function add($X, $Y, $Damage)
    {
        $result = [
            'success' => false
        ];
        //$X = $_POST['X'] ?? '';
        //$Y = $_POST['Y'] ?? '';
        //$Damage = 1;
        $Field_Id = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateSize($X)
            || !$this->validateSize($Y)
            || !$this->validateSize($Damage)
            || !$this->validateSize($Field_Id)
        )
        {
            $result['msg'] = 'Invalid slot parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new SlotService();
        $result = $service->saveSlot($X, $Y, $Field_Id, $Damage);

        echo json_encode($result, JSON_PRETTY_PRINT);

        //View::render('game');
    }

    public function getById()
    {
        $result = [
            'success' => false
        ];

        $slotId = $_POST['slotId'] ?? '0';

        if (!$this->validateSize($slotId)) {
            $result['msg'] = 'Invalid slot id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new SlotService();
        $result = $service->getSlot($slotId);

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function getAll()
    {
        $service = new SlotService();
        $result = $service->getAllSlots();

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateSize($size){
        return $size > 0;
    }
}