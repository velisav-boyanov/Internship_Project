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

        //echo json_encode($result, JSON_PRETTY_PRINT);
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

        return $result;
        //echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function getAll()
    {
        $service = new SlotService();
        $result = $service->getAllSlots();

        //echo json_encode($result, JSON_PRETTY_PRINT);
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

        //echo json_encode($result, JSON_PRETTY_PRINT);
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

    public function setRadar($x, $y){
        $slot = new SlotController();
        $thisSlot = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $x, $y);

        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $result['field'];
        $width = $field_elements['Width'];
        $length = $field_elements['Length'];

        $slotId = $thisSlot['Slot_Id'];
        //^get slot id^

        $radar = 0;

        for($i = 1; $i <= 8; $i++){
            if($i > 0 && $i < 4) {
                $new_y = $y + 1;
            }
            if($i == 4 || $i == 5){
                $new_y = $y;
            }
            if($i > 5 && $i < 9){
                $new_y = $y - 1;
            }
            if($i == 1 || $i == 6 || $i == 4){
                $new_x = $x - 1;
            }
            if($i == 3 || $i == 8 || $i == 5){
                $new_x = $x + 1;
            }
            if($i == 2 || $i == 7){
                $new_x = $x;
            }

            if(!(($new_x < 1 || $new_y < 1) || ($new_x > $width || $new_y > $length))){
                $newSlot = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $new_x, $new_y);

                //var_dump($newSlot['Damage']);
                //echo "<br>";
                if($newSlot['Damage'] > 0){
                    $radar++;
                }
            }
        }

        //^get bombs in vicinity

        $service = new SlotService();
        $service->setRadar($radar, $slotId);
    }

}