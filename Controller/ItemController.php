<?php


namespace Controller;
use Model\Services\ItemService;
use Core\View;

class ItemController
{
    const MinSize = 0;

    public function add($Name)
    {
        $result = [
            'success' => false
        ];
        $Player_Id = $_COOKIE['MyPlayerId'] ?? '';
        $Field_Id = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateSize($Player_Id)
            || !$this->validateSize($Field_Id)
        )
        {
            $result['msg'] = 'Invalid item parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new ItemService();
        $service->saveItem($Name, $Player_Id, $Field_Id);

    }

    public function getById()
    {
        $result = [
            'success' => false
        ];

        $itemId = $_POST['itemId'] ?? '0';

        if (!$this->validateSize($itemId)) {
            $result['msg'] = 'Invalid item id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new ItemService();
        $result = $service->getItem($itemId);

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function getAll()
    {
        $service = new ItemService();
        $result = $service->getAllItems();

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateSize($size){
        return $size > self::MinSize;
    }

    public function getSlotByFieldAndPlayerId($name)
    {
        $service = new ItemService();

        $fieldId = $_COOKIE['MyFieldId'];
        $playerId = $_COOKIE['MyPlayerId'];

        $result = $service->getSlotByFieldAndPlayerId($fieldId, $playerId, $name);

        return $result;
    }

    public function useItem($name)
    {
        $service = new ItemService();

        $fieldId = $_COOKIE['MyFieldId'];
        $playerId = $_COOKIE['MyPlayerId'];

        $service->useItem($fieldId, $playerId, $name);
    }
}