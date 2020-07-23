<?php


namespace Controller;
use Model\Services\FieldService;
use Model\Services\PlayerService;
use Core\View;

class PlayerController
{
    const MaxPlayerHealth = 4;
    const MinSize = 0;
    const itemChance = 13;

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

            return $result;
        }

        $service = new PlayerService();
        $result = $service->savePlayer($X, $Y, $Field_Id, $Health);

        View::render('game_setup');
    }

    public function getById($playerId)
    {
        $result = [
            'success' => false
        ];

        if (!$this->validateSize($playerId)) {
            $result['msg'] = 'Invalid player id';
            return $result;
        }

        $service = new PlayerService();
        $result = $service->getPlayer($playerId);

        return $result;
    }

    public function getAll()
    {
        $service = new PlayerService();
        $result = $service->getAllPlayers();

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
                $result['pos'] = $y - 1;
                break;
            case "right":
                $result['axis'] = 'Y';
                $result['pos'] = $y + 1;
                break;
            case "q":
                $player->useItem(-1);
                $result['axis'] = 'Y';
                $result['pos'] = $y;
                break;
            case "e":
                $player->useItem(-2);
                $result['axis'] = 'Y';
                $result['pos'] = $y;
                break;
            default:
                $result['axis'] = 'Y';
                $result['pos'] = $y;
                break;
        }

        return $result;
    }

    private function useItem($stat){
        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];

        $item = new ItemController();
        $service = new PlayerService();

        $damage = 0;
        $result = $item->getSlotByFieldAndPlayerId("small_health");

        if($result['msg'] == 1){
            $item->useItem("small_health");
            $damage = $stat;
        }

        $service->applyDamage($_COOKIE['MyPlayerId'], $damage, $health);
    }

    private function validateSize($size){
        return $size > self::MinSize;
    }

    private function validateXAxis($x){
        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $result['field'];
        $field_width = $field_elements['Width'];

        return $x <= $field_width;
    }

    private function validateHealth($health){
        return ($health > self::MinSize && $health <= self::MaxPlayerHealth);
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

    private function validateWin($pos, $axis){
        $field = new FieldController();
        $array1 = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $array1['field'];
        $field_x = $field_elements['End_X'];
        $field_y = $field_elements['End_Y'];

        $player = new PlayerController();
        $array2 = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array2['player'];
        $x = $elements['X'];
        $y = $elements['Y'];

        if(($x == $field_x
        || ($axis == 'X'
        && $pos == $field_x))
        && ($y == $field_y
        || ($axis == 'Y'
        && $pos == $field_y))){
            $this->endGame();

            return 1;
        }
    }

    private function scan(){
        $slot = new SlotController();

        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $result['field'];
        $x = $field_elements['Width'];
        $y = $field_elements['Length'];

        for($i = 1; $i <= $x; $i++){
            for($k = 1; $k <= $y; $k++){
                $slot->setRadar($i, $k);
            }
        }
    }

    public function move(){
        $this->scan();

        $slot = new SlotController();
        $service = new PlayerService();

        $whereTo = $this->whereTo($_POST['Input']);
        $whichPlayer = $_COOKIE['MyPlayerId'];


        if(!$this->validatePosition($whereTo['pos'], $whereTo['axis'])){
            $result['msg'] = 'Out of bounds.';

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $result = $service->move($whereTo, $whichPlayer);
        $slot->find();

        if($this->validateWin($whereTo['pos'], $whereTo['axis']) == 1){
            $result['msg'] = 'You won.';

            $slot->removeSlots();
            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $this->applyDamage();

        if($this->isDead() == 1){
            $result['msg'] = 'YOU DIED.';
            $slot->removeSlots();

            echo json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        }

        $slot->emptyBomb();

        View::render('game');
    }

    private function endGame(){
        $service = new PlayerService();

        $whichPlayer = $_COOKIE['MyPlayerId'];

        $service->endGame($whichPlayer);
    }

    private function applyDamage(){
        $service = new PlayerService();

        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];
        $x = $elements['X'];
        $y = $elements['Y'];

        $slot = new SlotController();
        $damageSlot = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $x, $y);
        $damage = $damageSlot['Damage'];

        $this->addItem($damage);

        $service->applyDamage($_COOKIE['MyPlayerId'], $damage, $health);
    }

    private function addItem($damage){
        $slot = new ItemController();
        if($damage == 0){
            $random1 = mt_rand(1, 100);
            $random2 = mt_rand(1, 30);
            if($random1 < self::itemChance){
                if($random2 < 30){
                    $name = "small_health";
                }
                if($random2 < 15){
                    $name = "big_health";
                }
                if($random2 == 30 || $random2 == 20 || $random2 == 10){
                    $name = "radar";
                }
                $slot->add($name);
            }
        }
    }

    private function isDead(){
        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];

        if($health == 0){
            return 1;
        }
        return 0;
    }

}