<?php


namespace Controller;
use Core\View;
use Model\Services\SlotService;

class SlotController
{
    const MinSize = 0;

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
            || !$this->validateDamage($Damage)
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
        return $size > self::MinSize;
    }

    private function validateDamage($size){
        return $size >= self::MinSize;
    }

    public function getDamageByFieldXY($fieldId, $x, $y)
    {
        $service = new SlotService();
        $result = $service->getDamageByFieldXY($fieldId, $x, $y);

        echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
    }

    public function find()
    {
        $player = new PlayerController();

        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $x = $elements['X'];
        $y = $elements['Y'];

        $slot = new SlotController();
        $thisSlot = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $x, $y);

        $slotId = $thisSlot['Slot_Id'];

        $service = new SlotService();
        $service->find($slotId);
    }

}