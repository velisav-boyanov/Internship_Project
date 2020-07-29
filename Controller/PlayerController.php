<?php


namespace Controller;
use Model\Services\FieldService;
use Model\Services\PlayerService;
use Core\View;

class PlayerController
{
    const MAX_PLAYER_HEALTH = 4;
    const MIN_SIZE = 0;
    const ITEM_CHANCE = 20;
    const SMALL_HEALTH = -1;
    const LARGE_HEALTH = -2;
    const Y_AXIS = 'Y';
    const X_AXIS = 'X';
    const SMALL_HEALTH_STRING = "small_health";
    const BIG_HEALTH_STRING = "big_health";
    const RADAR_STRING = "radar";
    const PRICES = [
        'RADAR' => 12,
        'SMALL_POTION' => 3,
        'BIG_POTION' => 6
    ];
    const ITEM_CHANCES = [
        'SMALL_HEALTH' => 15,
        'LARGE_HEALTH' => 7,
        'RADAR' => 3
    ];


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
        $result1 = $service->savePlayer($x, $y, $fieldId, $health);

        $game = new GameController();
        $result2 = $game->setUp();

        View::redirect('index.php?target=player&action=loadGame');
    }

    public function loadGame(){
        View::render('game');
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
        $array = $this->getById($_COOKIE['MyPlayerId']);
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
                $this->useItem(self::SMALL_HEALTH, 1);
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "e":
                $this->useItem(self::LARGE_HEALTH, 0);
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "r":
                $slot = new SlotController();
                $slot->findAll();

                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;

                $item = new ItemController();
                $item->useItem(self::RADAR_STRING);

                break;
            case "bs":
                $this->buyItem("bs");
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "bl":
                $this->buyItem("bl");
                $result['axis'] = self::Y_AXIS;
                $result['pos'] = $y;
                break;
            case "br":
                $this->buyItem("br");
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
        $info = $this->getById($_COOKIE['MyPlayerId']);
        $coins = $info['player']['Coins'];
        $price = 0;
        switch ($type) {
            case("bs"):
                $price = self::PRICES['SMALL_POTION'];
                $type = self::SMALL_HEALTH_STRING;
                break;
            case("bl"):
                $price = self::PRICES['BIG_POTION'];
                $type = self::BIG_HEALTH_STRING;
                break;
            case("radar"):
                $price = self::PRICES['RADAR'];
                $type = self::RADAR_STRING;
                break;
        }
        if($price <= $coins){
            $this->alterCoins($price*(-1));
            $item = new ItemController();
            $item->add($type);
        }
    }

    private function useItem($stat, $small){
        $array = $this->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];

        $item = new ItemController();
        $service = new PlayerService();

        if($small == 1) {
            $result = $item->getSlotByFieldAndPlayerId(self::SMALL_HEALTH_STRING);
        }
        if($small == 0) {
            $result = $item->getSlotByFieldAndPlayerId(self::BIG_HEALTH_STRING);
        }
        if($result['success'] == true){
            if($small == 1) {
                $item->useItem(self::SMALL_HEALTH_STRING);
                $damage = $stat;
            }
            if($small == 0) {
                $item->useItem(self::BIG_HEALTH_STRING);
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
        //TODO cookie not set

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

        $array2 = $this->getById($_COOKIE['MyPlayerId']);
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

                return true;
        }
        return false;
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

        if($this->validateWin($whereTo['pos'], $whereTo['axis']) == true){
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

        $array = $this->getById($_COOKIE['MyPlayerId']);
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
        $playerInfo = $this->getById($_COOKIE['MyPlayerId']);

        $slot = new ItemController();
        $item = new SlotController();
        $result = $item->getDamageByFieldXY($_COOKIE['MyFieldId'], $playerInfo['player']['X'], $playerInfo['player']['Y']);

        if($result['Found'] == 0) {
            if ($damage == 0) {
                $random1 = mt_rand(1, 100);
                $random2 = mt_rand(1, 30);
                if ($random1 < self::ITEM_CHANCE) {
                    if ($random2 < self::ITEM_CHANCES['SMALL_HEALTH']) {
                        $name = self::SMALL_HEALTH_STRING;
                    }
                    if ($random2 < self::ITEM_CHANCES['LARGE_HEALTH']) {
                        $name = self::BIG_HEALTH_STRING;
                    }
                    if ($random2 < self::ITEM_CHANCES['RADAR']) {
                        $name = self::RADAR_STRING;
                    }
                    if(isset($name)) {
                        $slot->add($name);
                    }
                    if($random2 >= 15) {
                        $this->alterCoins(1);
                    }
                }
            }
        }
    }

    private function isDead(){
        $array = $this->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $health = $elements['Health'];

        if($health == 0){
            return 1;
        }
        return 0;
    }

    private function alterCoins($num){
        $array = $this->getById($_COOKIE['MyPlayerId']);
        $elements = $array['player'];
        $coins = $elements['Coins'];
        $num1 = $coins + $num;
        $player = new PlayerService();
        $player->alterCoins($num1, $_COOKIE['MyPlayerId']);
    }

}