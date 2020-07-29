
<?php
use Controller\ItemController;
use Controller\SlotController;
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
    <p>
    <?php
        $elements = (new Controller\GameController)->getInfo('MyPlayerId');
        $playerX = $elements['x'];
        $playerY = $elements['y'];
        (new Controller\SlotController)->firstFind($playerX, $playerY);

        $player_health = $elements['Health'];
        $player_coins = $elements['Coins'];
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
    ?>
    </p>

    <p>
        <?php
            $elements = (new Controller\GameController)->getInfo('MyFieldId');
            $fieldX = $elements['x'];
            $fieldY = $elements['y'];

            echo "<table border = '1', bgcolor='#d3d3d3'>";
            for($i = 1; $i <= $fieldX; $i++){
                echo "<tr>";
                for($k = 1; $k <= $fieldY; $k++){
                    $slot = new SlotController();
                    $elements = $slot->getDamageByFieldXY($_COOKIE['MyFieldId'], $i, $k);

                    if(($k == $playerY) and ($i == $playerX)){
                        if($elements['Found'] == 1) {
                            if($elements['Radar'] != NULL) {
                                echo "<td align = center>";
                                echo '<font color="red">' . $elements['Radar'] . " " . '</font>';
                                echo "</td>";
                                //echo 'P' . $elements['Radar'];
                            }else{
                                echo "<td align = center>";
                                echo '<font color="red">' . "# " . '</font>';
                                echo "</td>";
                            }
                        }
                    }else{
                        if($elements['Found'] == 1){
                            echo "<td align = center>";
                            echo $elements['Radar'] . " ";
                            echo "</td>";
                        }else{
                            echo "<td align = center>";
                            echo '# ';
                            echo "</td>";
                        }
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </p>
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
