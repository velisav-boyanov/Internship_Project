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

        View::render('game_setup');
    }

    public function getById($playerId)
    {
        $result = [
            'success' => false
        ];

        //$playerId = $_POST['playerId'] ?? '0';

        if (!$this->validateSize($playerId)) {
            $result['msg'] = 'Invalid player id';
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $service = new PlayerService();
        $result = $service->getPlayer($playerId);

        echo json_encode($result, JSON_PRETTY_PRINT);
        return $result;
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

    private function whereTo($whereTo){
        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $x = $elements['X'];
        $y = $elements['Y'];

        switch ($whereTo) {
            //the map is printed upside down
            case "up":
                $result['axis'] = 'X';
                $result['pos'] = $x - 1;
                break;
            case "down":
                $result['axis'] = 'X';
                $result['pos'] = $x + 1;
                break;
            case "left":
                $result['axis'] = 'Y';
                $result['pos'] = $y + 1;
                break;
            case "right":
                $result['axis'] = 'Y';
                $result['pos'] = $y - 1;
                break;
        }

        return $result;
    }

    private function validatePosition($pos, $axis){
        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $result['field'];
        $field_x = $field_elements['Width'];
        $field_y = $field_elements['Length'];

        if($axis == 'X') {
            return ($pos <= $field_x && $pos > 0);
        }
        if($axis == 'Y') {
            return ($pos <= $field_y && $pos > 0);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////
    public function move(){
        $service = new PlayerService();

        $whereTo = $this->whereTo($_POST['Input']);
        $whichPlayer = $_COOKIE['MyPlayerId'];


        if(!$this->validatePosition($whereTo['pos'], $whereTo['axis'])){
            $result['msg'] = 'Out of bounds.';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $result = $service->move($whereTo, $whichPlayer);

        echo json_encode($result, JSON_PRETTY_PRINT);
        View::render('game');
    }

}