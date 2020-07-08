<?php


namespace Model\Services;
use Model\Repository\SlotRepository;

class SlotService
{
    public function saveSlot($X, $Y, $Field_Id, $Damage)
    {
        $result = ['success' => false];

        $repo = new SlotRepository();

        $slotToInsert = [
            'X' => $X,
            'Y' => $Y,
            'Field_Id' => $Field_Id,
            'Damage' => $Damage
        ];

        if($repo->saveSlot($slotToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Slot successfully added!';
        }
        return $result;
    }

    public function getSlot($slotId)
    {
        $result = [
            'success' => false
        ];

        $repo = new SlotRepository();
        $slot = $repo->getSlotById($slotId);

        if (!$slot) {
            $result['msg'] = 'Slot with id ' . $slotId . ' was not found!';
            return $result;
        }

        $result['success'] = true;
        $result['slot'] = $slot;
        return $result;
    }

    public function getAllSlots()
    {
        $result = [
            'success' => false
        ];

        $repo = new SlotRepository();
        $slot = $repo->getAllSlots();

        if (!$slot) {
            $result['msg'] = 'There are no slots yet!';
            return $result;
        }

        $result['success'] = true;
        $result['slot'] = $slot;
        return $result;
    }
}