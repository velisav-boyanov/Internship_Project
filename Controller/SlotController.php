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

    public function getById($slotId)
    {
        $result = [
            'success' => false
        ];


        if (!$this->validateSize($slotId)) {
            $result['msg'] = 'Invalid slot id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new SlotService();
        $result = ['slot' => $service->getSlot($slotId)];

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

    public function emptyBomb()
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
        $service->emptyBomb($slotId);
    }

    public function removeSlots()
    {
        $id = $_COOKIE['MyFieldId'];

        $service = new SlotService();
        $service->removeSlots($id);
    }

    public function setRadar(){
        $player = new PlayerController();

        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $x = $elements['X'];
        $y = $elements['Y'];

        $slot = new SlotController();
        $thisSlot = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $x, $y);

        $slotId = $thisSlot['Slot_Id'];
        //^get slot id^

        $radar = 0;
        for($i = 0; $i < 8; $i++){
            switch ($i) {
                case 0:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] - 1;
                    $y = $info2['Y'] + 1;
                    break;
                case 1:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'];
                    $y = $info2['Y'] + 1;
                    break;
                case 2:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] + 1;
                    $y = $info2['Y'] + 1;
                    break;
                case 3:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] - 1;
                    $y = $info2['Y'];
                    break;
                case 4:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] + 1;
                    $y = $info2['Y'];
                    break;
                case 5:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] - 1;
                    $y = $info2['Y'] - 1;
                    break;
                case 6:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'];
                    $y = $info2['Y'] - 1;
                    break;
                case 7:
                    $info1 = $slot->getById($slotId);
                    $info2 = $info1['slot'];
                    $x = $info2['X'] + 1;
                    $y = $info2['Y'] - 1;
                    break;
            }

            $service = new SlotService();
            $result = $service->getDamageByFieldXY($_COOKIE['MyFieldId'], $x, $y);
            $radar += $result['Damage'];
        }
        //^get bombs in vicinity

        $service = new SlotService();
        $service->setRadar($radar, $slotId);
    }

}