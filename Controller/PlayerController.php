<?php


namespace Controller;
use Model\Services\PlayerService;
use Core\View;

class PlayerController
{
    public function add()
    {
        $result = [
            'success' => false
        ];

        $X = $_POST['X'] ?? '';
        $Y = $_POST['Y'] ?? '';
        $Health = $_POST['Health'] ?? '';
        $Field_Id = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateSize($X)
            || !$this->validateSize($Y)
            || !$this->validateSize($Health)
            || !$this->validateSize($Field_Id)
        )
        {
            $result['msg'] = 'Invalid player parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new PlayerService();
        $result = $service->savePlayer($X, $Y, $Field_Id, $Health);

        echo json_encode($result, JSON_PRETTY_PRINT);

        View::render('player');
    }

    public function getById()
    {
        $result = [
            'success' => false
        ];

        $playerId = $_POST['playerId'] ?? '0';

        if (!$this->validateSize($playerId)) {
            $result['msg'] = 'Invalid player id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new PlayerService();
        $result = $service->getPlayer($playerId);

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function getAll()
    {
        $service = new PlayerService();
        $result = $service->getAllPlayers();

        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateSize($size){
        return $size > 0;
    }

}