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

        if($repo->savePlayer($playerToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Player successfully added!';
        }
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
}