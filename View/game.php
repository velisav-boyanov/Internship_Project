<?php echo "<br>" ?>

<?php

use Controller\FieldController;
use Controller\SlotController;

$field = new FieldController();
$result = $field->getById($_COOKIE['MyFieldId']);

$field_elements = $result['field'];
$field_x = $field_elements['Width'];
$field_y = $field_elements['Length'];
$field_bomb_chance = $field_elements['Bomb_Intensity'];

for($i = 1; $i <= $field_x; $i++) {
    for($k = 1; $k <= $field_y; $k++)
    {
        echo "<br>" . "Loading...";

        $bomb = isBomb($field_bomb_chance);

        $slot = new SlotController();
        $slot->add($i, $k, $bomb);
    }
}
echo "<br>" . "Finished" . "<br>";



function isBomb($percentage){
    return 1;
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
<h>Bruhhhhh</h>
</body>
</html>