<?php


namespace Model\Services;
use Model\Repository\ItemRepository;

class ItemService
{
    public function saveItem($Name, $Player_Id, $Field_Id)
    {
        $result = ['success' => false];

        $repo = new ItemRepository();

        $itemToInsert = [
            'Player_Id'=> $Player_Id,
            'Field_Id' => $Field_Id,
            'Name' => $Name
        ];

        if($repo->saveItem($itemToInsert))
        {
            $result['success'] = true;
            $result['msg'] = 'Item successfully added!';
        }
        return $result;
    }

    public function getItem($itemId)
    {
        $result = [
            'success' => false
        ];

        $repo = new ItemRepository();
        $item = $repo->getItemById($itemId);

        if (!$item) {
            $result['msg'] = 'Item with id ' . $itemId . ' was not found!';
            return $result;
        }

        $result['success'] = true;
        $result['item'] = $item;
        return $result;
    }

    public function getAllItems($playerId)
    {
        $result = [
            'success' => false
        ];

        $repo = new ItemRepository();
        $item = $repo->getAllItems($playerId);

        if (!$item) {
            $result['msg'] = 'There are no items yet!';
            return $result;
        }

        $result['success'] = true;
        $result['item'] = $item;
        return $result;
    }

    public function getSlotByFieldAndPlayerId($fieldId, $playerId, $name)
    {
        $repo = new ItemRepository();
        $item = $repo->getSlotByFieldAndPlayerId($fieldId, $playerId, $name);

        if (!$item) {
            $result['msg'] = 'No such item exists.';
            $result['success'] = false;
            return $result;
        }else{
            $result['success'] = true;
        }
        $result['item'] = $item;
        return $result;
    }

    public function useItem($fieldId, $playerId, $name)
    {
        $repo = new ItemRepository();
        $repo->useItem($fieldId, $playerId, $name);

    }

    public function removeItems($fieldId){
        $repo = new ItemRepository();
        $repo->removeItems($fieldId);
    }

    public function getNumberOfItem($playerId, $name)
    {
        $repo = new ItemRepository();
        $item = $repo->getNumberOfItem($name, $playerId);

        if (!$item) {
            $result['msg'] = 'No such item exists.';
            $result['success'] = false;
            return $result;
        }else{
            $result['success'] = true;
        }
        $result['number'] = $item;
        return $result;
    }
}