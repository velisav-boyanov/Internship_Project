<?php


namespace Controller;
use Model\Services\FieldService;
use Model\Services\PlayerService;
use Core\View;

class PlayerController
{
    const MAX_PLAYER_HEALTH = 4;
    const MIN_SIZE = 0;
    const ITEM_CHANCE = 100;
    const SMALL_HEALTH = -1;
    const LARGE_HEALTH = -2;
    const Y_AXIS = 'Y';
    const X_AXIS = 'X';
    const S = "small_health";
    const L = "big_health";
    const R = "radar";
    const C ="coin";


    public function add()
    {
        $result = [
            'success' => false
        ];
        $baseYAxis = 1;
        $x = $_POST['X'] ?? '';
        $y = $baseYAxis ?? '';
        $health = $_POST['Health'] ?? '';
        $fieldId = $_COOKIE['MyFieldId'] ?? '';

        if(
            !$this->validateXAxis($x)
            || !$this->validateSize($y)
            || !$this->validateHealth($health)
            || !$this->validateSize($fieldId)
        )
        {
            $result['msg'] = 'Invalid player parameters';

            return $result;
        }

        $service = new PlayerService();
        $result = $service->savePlayer($x, $y, $fieldId, $health);

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
                $result['axis'] = self::X_AXIS;
                $result['pos'] = $x - 1;
                break;
            case "down":
                $result['axis'] = self::X_AXIS;
                $result['pos'] = $x + 1;
                break;
            case "left":
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y - 1;
                break;
            case "right":
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y + 1;
                break;
            case "q":
                $player->useItem(self::SMALL_HEALTH, 1);
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "e":
                $player->useItem(self::LARGE_HEALTH, 0);
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "r":
                $slot = new SlotController();
                $slot->findAll();

                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;

                $item = new ItemController();
                $item->useItem("radar");

                break;
            case "bs":
                $player->buyItem("bs");
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "bl":
                $player->buyItem("bl");
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "br":
                $player->buyItem("br");
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            default:
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
        }

        return $result;
    }

    private function buyItem($type){
        $player = new PlayerController();
        $info = $player->getById($_COOKIE['MyPlayerId']);
        $coins = $info['player']['Coins'];
        $price = 0;
        switch ($type) {
            case("bs"):
                $price = 3;
                $type = self::S;
                break;
            case("bl"):
                $price = 6;
                $type = self::L;
                break;
            case("radar"):
                $price = 12;
                $type = self::R;
                break;
        }
        if($price <= $coins){
            $player->alterCoins($price*(-1));
            $item = new ItemController();
            $item->add($type);
        }
    }

    private function useItem($stat, $small){
        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];

        $item = new ItemController();
        $service = new PlayerService();

        if($small == 1) {
            $result = $item->getSlotByFieldAndPlayerId(self::S);
        }
        if($small == 0) {
            $result = $item->getSlotByFieldAndPlayerId(self::L);
        }
        if($result['success'] == true){
            if($small == 1) {
                $item->useItem(self::S);
                $damage = $stat;
            }
            if($small == 0) {
                $item->useItem(self::L);
                $damage = $stat;
            }
        }

        $service->applyDamage($_COOKIE['MyPlayerId'], $damage, $health);
    }

    private function validateSize($size){
        return $size > self::MIN_SIZE;
    }

    private function validateXAxis($x){
        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $fieldElements = $result['field'];
        $fieldWidth = $fieldElements['Width'];

        return $x <= $fieldWidth;
    }

    private function validateHealth($health){
        return ($health > self::MIN_SIZE && $health <= self::MAX_PLAYER_HEALTH);
    }

    private function validatePosition($pos, $axis){
        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $fieldElements = $result['field'];
        $fieldX = $fieldElements['Width'];
        $fieldY = $fieldElements['Length'];

        if($axis == self::X_AXIS) {
            return ($pos <= $fieldX && $pos > 0);
        }
        if($axis == self::Y_AXIS) {
            return ($pos <= $fieldY && $pos > 0);
        }
    }

    private function validateWin($pos, $axis){
        $field = new FieldController();
        $array1 = $field->getById($_COOKIE['MyFieldId']);

        $fieldElements = $array1['field'];
        $fieldX = $fieldElements['End_X'];
        $fieldY = $fieldElements['End_Y'];

        $player = new PlayerController();
        $array2 = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array2['player'];
        $x = $elements['X'];
        $y = $elements['Y'];

        if(($x == $fieldX
        || ($axis == self::X_AXIS
        && $pos == $fieldX))
        && ($y == $fieldY
        || ($axis == self::Y_AXIS
        && $pos == $fieldY))){
            $this->endGame();

            return 1;
        }
    }

    private function scan(){
        $slot = new SlotController();

        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $fieldElements = $result['field'];
        $x = $fieldElements['Width'];
        $y = $fieldElements['Length'];

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
        $item = new ItemController();

        $whereTo = $this->whereTo($_POST['Input']);
        $whichPlayer = $_COOKIE['MyPlayerId'];


        if(!$this->validatePosition($whereTo['pos'], $whereTo['axis'])){
            $result['msg'] = 'Out of bounds.';

            echo $result['msg'];
            return $result;
        }

        $result = $service->move($whereTo, $whichPlayer);

        if($this->validateWin($whereTo['pos'], $whereTo['axis']) == 1){
            $result['msg'] = 'You won.';

            $slot->removeSlots();
            $item->removeItems();
            echo $result['msg'];
            return $result;
        }

        $this->applyDamage();

        if($this->isDead() == 1){
            $result['msg'] = 'YOU DIED.';
            $slot->removeSlots();
            $item->removeItems();
            echo $result['msg'];
            return $result;
        }

        $slot->emptyBomb();
        $slot->find();

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
        $player = new PlayerController();
        $playerInfo = $player->getById($_COOKIE['MyPlayerId']);

        $slot = new ItemController();
        $item = new SlotController();
        $result = $item->getDamageByFieldXY($_COOKIE['MyFieldId'], $playerInfo['player']['X'], $playerInfo['player']['Y']);

        if($result['Found'] == 0) {
            if ($damage == 0) {
                $random1 = mt_rand(1, 100);
                $random2 = mt_rand(1, 30);
                if ($random1 < self::ITEM_CHANCE) {
                    if ($random2 < 15) {
                        $name = self::S;
                    }
                    if ($random2 < 7) {
                        $name = self::L;
                    }
                    if ($random2 == 30 || $random2 == 20 || $random2 == 10) {
                        $name = self::R;
                    }
                    if(isset($name)) {
                        $slot->add($name);
                    }
                    if($random2 >= 15) {
                        $player->alterCoins(1);
                    }
                }
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

    private function alterCoins($num){
        $player = new PlayerController();
        $array = $player->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $coins = $elements['Coins'];
        $num1 = $coins + $num;
        $player = new PlayerService();
        $player->alterCoins($num1, $_COOKIE['MyPlayerId']);
    }

}