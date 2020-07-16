<?php


namespace Model\Services;

use Model\Repository\PlayerRepository;

class PlayerService
{
    public function savePlayer($X, $Y, $Field_Id, $Health)
    {
        $result = ['success' => false];

        $repo = new PlayerRepository();

        $playerToInsert = [
            'X' => $X,
            'Y' => $Y,
            'Field_Id' => $Field_Id,
            'Health' => $Health
        ];

        //$playerId = $repo->savePlayer($playerToInsert);
        if($playerId = $repo->savePlayer($playerToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Player successfully added!';
        }
        //COOKIE
        $cookie_name = 'MyPlayerId';
        var_dump($playerId);
        $date = time() + (60*60*24*7*2);
        setcookie($cookie_name, $playerId, $date);

        return $result;
    }

    public function getPlayer($playerId)
    {
        $result = [
            'success' => false
        ];

        $repo = new PlayerRepository();
        $player = $repo->getPlayerById($playerId);

        if (!$player) {
            $result['msg'] = 'Player with id ' . $playerId . ' was not found!';
            return $result;
        }

        $result['success'] = true;
        $result['player'] = $player;
        return $result;
    }

    public function getAllPlayers()
    {
        $result = [
            'success' => false
        ];

        $repo = new PlayerRepository();
        $player = $repo->getAllPlayers();

        if (!$player) {
            $result['msg'] = 'There are no players yet!';
            return $result;
        }

        $result['success'] = true;
        $result['player'] = $player;
        return $result;
    }
    /////////////////////////////////////////////////////////////////////////////////
    public function move($whereTo, $whichPlayer){
        $result = [
            'success' => false
        ];

        $repo = new PlayerRepository();
        $axis = $whereTo['axis'];
        $pos = $whereTo['pos'];
        $repo->move($pos, $axis, $whichPlayer);

        $result['success'] = true;
        return $result;
    }

    public function endGame($whichPlayer){
        $repo = new PlayerRepository();
        $repo->endGame($whichPlayer);
    }

    public function getDamage($whichPlayer, $damage, $health){
        $repo = new PlayerRepository();
        $repo->getDamage($whichPlayer, $damage, $health);
    }
}