<?php


namespace Controller;
use Model\Services\ItemService;
use Core\View;

class ItemController
{
    const MinSize = 0;

    public function add($name)
    {
        $result = [
            'success' => false
        ];
        $playerId = $_COOKIE['MyPlayerId'] ?? '';
        $fieldId = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateSize($playerId)
            || !$this->validateSize($fieldId)
        )
        {
            $result['msg'] = 'Invalid item parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new ItemService();
        $service->saveItem($name, $playerId, $fieldId);

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
        $result = $service->getAllItems($_COOKIE['MyPlayerId']);

        //echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
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

    public function removeItems()
    {
        $id = $_COOKIE['MyFieldId'];

        $service = new ItemService();
        $service->removeItems($id);
    }

    public function getNumberOfItem($name){
        $service = new ItemService();

        $playerId = $_COOKIE['MyPlayerId'];

        $result = $service->getNumberOfItem($playerId, $name);

        return $result;
    }
}