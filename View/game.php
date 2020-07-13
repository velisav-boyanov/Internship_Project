<?php

use Controller\FieldController;
use Controller\PlayerController;
use Core\View;

$elements1 = getInfo('MyFieldId');
$field_x = $elements1['x'];
$field_y = $elements1['y'];

$elements2 = getInfo('MyPlayerId');
$player_x = $elements2['x'];
$player_y = $elements2['y'];

//echo $field_y . $field_x . $player_y . $player_x;
echo "<br>";
for($i = 1; $i <= $field_x; $i++){
    for($k = 1; $k <= $field_y; $k++){
        //echo "#";
        if(($k == $player_y) and ($i == $player_x)){
            echo 'P' /*. $num*/ . ' ';
        }else{
            echo '# ';
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
        $result['status'] = "true";
    }elseif($type = 'MyFieldId') {
        $elements = $obj['field'];

        $result['x'] = $elements['Width'];
        $result['y'] = $elements['Length'];
        $result['status'] = "true";
    }
    return $result;
}

echo "Game go zoom.";

