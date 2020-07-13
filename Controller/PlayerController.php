<?php


namespace Controller;
use Model\Services\FieldService;
use Model\Services\PlayerService;
use Core\View;

class PlayerController
{
    const MaxPlayerHealth = 4;
    const MinSize = 0;

    public function add()
    {
        $result = [
            'success' => false
        ];
        $baseYAxis = 1;
        $X = $_POST['X'] ?? '';
        $Y = $baseYAxis ?? '';
        $Health = $_POST['Health'] ?? '';
        $Field_Id = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateXAxis($X)
            || !$this->validateSize($Y)
            || !$this->validateHealth($Health)
            || !$this->validateSize($Field_Id)
        )
        {
            $result['msg'] = 'Invalid player parameters';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new PlayerService();
        $result = $service->savePlayer($X, $Y, $Field_Id, $Health);

        echo "<br>";
        echo json_encode($result, JSON_PRETTY_PRINT);

        View::render('game');
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
        return $size > self::MinSize;
    }

    private function validateXAxis($x){
        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);
        //var_dump($field_elements);
        $field_elements = $result['field'];
        $field_width = $field_elements['Width'];

        return $x <= $field_width;
    }

    private function validateHealth($health){
        return ($health > self::MinSize && $health <= self::MaxPlayerHealth);
    }
}