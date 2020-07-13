<?php echo "<br>" ?>

<?php
use Controller\FieldController;
use Controller\SlotController;
use Core\View;

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

        //No bombs on the first row, because the user spawns there.
        if($i == 1) {
            $bomb = 0;
        }elseif($i > 1){
            $bomb = isBomb($field_bomb_chance);
        }

        $slot = new SlotController();
        $slot->add($i, $k, $bomb);
    }
}
echo "<br>" . "Finished" . "<br>";
//endSetUp();

//Sets damage for field slot.
function isBomb($percentage){
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

function endSetUp(){
    View::render('game');
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
    <form action="index.php?target=game&action=start" method="post">
        <button type="submit" class="btn btn-primary"> Start </button>
    </form>
</body>
</html>