<?php

use Controller\FieldController;
use Controller\ItemController;
use Controller\PlayerController;
use Controller\SlotController;
use Core\View;

$elements1 = getInfo('MyFieldId');
$field_x = $elements1['x'];
$field_y = $elements1['y'];

$elements2 = getInfo('MyPlayerId');
$player_x = $elements2['x'];
$player_y = $elements2['y'];

$slot = new SlotController();
$slot->firstFind($player_x, $player_y);

$player_health = $elements2['Health'];
$player_coins = $elements2['Coins'];
$item = new ItemController();
$items = $item->getAll();
echo "Player health: " . $player_health . ", " . "Player coins: " . $player_coins;
echo "<br>";
if(!isset($items['msg'])){
    $number = $item->getNumberOfItem('small_health');
    echo "Small potions: " . $number['number']['COUNT(`Item_Id`)'];
    echo "<br>";
    $number = $item->getNumberOfItem('big_health');
    echo "Large potions: " . $number['number']['COUNT(`Item_Id`)'];
    echo "<br>";
    $number = $item->getNumberOfItem('radar');
    echo "Radars: " . $number['number']['COUNT(`Item_Id`)'];

}else{
    echo $items['msg'];
}
echo "<br>" . "<br>";

for($i = 1; $i <= $field_x; $i++){
    for($k = 1; $k <= $field_y; $k++){
        $slot = new SlotController();
        $elements = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $i, $k);

        if(($k == $player_y) and ($i == $player_x)){
            if($elements['Found'] == 1) {
                echo 'P' . $elements['Radar'];
            }else{
                echo 'P  ';
            }
        }else{
            if($elements['Found'] == 1){
                echo $elements['Radar'] . " ";
            }else{
                echo '#  ';
            }
        }
    }
    echo "<br>";
}

//gets the x and y for fields or players
function getInfo($type){
    $result['status'] = "false";

    $object = new PlayerController();
    if($type == 'MyFieldId') {
        $object = new FieldController();
    }

    $obj = $object->getById($_COOKIE[$type]);
    
    if($type == 'MyPlayerId') {
        $elements = $obj['player'];

        $result['x'] = $elements['X'];
        $result['y'] = $elements['Y'];
        $result['Health'] = $elements['Health'];
        $result['Coins'] = $elements['Coins'];
        $result['status'] = "true";
    }elseif($type = 'MyFieldId') {
        $elements = $obj['field'];

        $result['x'] = $elements['Width'];
        $result['y'] = $elements['Length'];
        $result['status'] = "true";
    }
    return $result;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="index.php?target=player&action=move" method="post">
        <label>Enter input:</label>
        <input type="text" name = "Input" class="form-control" required>
        <button type="submit" class="btn btn-primary"> Move </button>
    </form>
    <h4>Controls: up, left, right, down;</h4>
    <h4>q: small health potion, e: large health potion, r: radar;</h4>
    <h4>bs: buy small health potion, bl: buy large health potion, br: buy radar.</h4>
</body>
</html>
