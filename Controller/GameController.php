<?php


namespace Controller;


use Controller\FieldController;
use Controller\SlotController;
use Core\View;

class GameController
{
    public function start()
    {
        View::render('game');
    }

    public function setUp(){

        $field = new FieldController();
        $result = $field->getById($_COOKIE['MyFieldId']);

        $field_elements = $result['field'];
        $field_x = $field_elements['Width'];
        $field_y = $field_elements['Length'];
        $field_bomb_chance = $field_elements['Bomb_Intensity'];

        for($i = 1; $i <= $field_x; $i++) {
            for($k = 1; $k <= $field_y; $k++)
            {
                if($i == 1) {
                    $bomb = 0;
                }elseif($i > 1){
                    $bomb = $this->isBomb($field_bomb_chance);
                }

                $slot = new SlotController();
                $slot->add($i, $k, $bomb);
            }
        }
        return true;
    }

    public function isBomb($percentage){
        $random = mt_rand(1, 100) / 100;

        $result = 0;

        if($random <= $percentage){
            $result = 1;
        }
        if($random <= ($percentage*2)/3){
            $result = 2;
        }
        if($random <= $percentage/3){
            $result = 3;
        }
        return $result;
    }
    //gets the x and y for fields or players
    public function getInfo($type)
    {
        $result['status'] = "false";

        $object = new PlayerController();
        if ($type == 'MyFieldId') {
            $object = new FieldController();
        }

        $obj = $object->getById($_COOKIE[$type]);

        if ($type == 'MyPlayerId') {
            $elements = $obj['player'];

            $result['x'] = $elements['X'];
            $result['y'] = $elements['Y'];
            $result['Health'] = $elements['Health'];
            $result['Coins'] = $elements['Coins'];
            $result['status'] = "true";
        } elseif ($type = 'MyFieldId') {
            $elements = $obj['field'];

            $result['x'] = $elements['Width'];
            $result['y'] = $elements['Length'];
            $result['status'] = "true";
        }
        return $result;
    }
}