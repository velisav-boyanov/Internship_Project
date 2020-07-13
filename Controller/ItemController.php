<?php


namespace Controller;
use Model\Services\ItemService;
use Core\View;

class ItemController
{
    const MinSize = 0;

    public function add()
    {
        $result = [
            'success' => false
        ];
        $Name = $_POST['Name'] ?? '';
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
        $result = $service->saveItem($Name, $Field_Id, $Player_Id);

        echo json_encode($result, JSON_PRETTY_PRINT);

        //View::render('game_setup');
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
}