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

    public function getAllItems()
    {
        $result = [
            'success' => false
        ];

        $repo = new ItemRepository();
        $item = $repo->getAllItems();

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
            return $result;
        }
        $result['item'] = $item;
        return $result;
    }

    public function useItem($fieldId, $playerId, $name)
    {
        $repo = new ItemRepository();
        $item = $repo->useItem($fieldId, $playerId, $name);

        if (!$item) {
            $result['msg'] = 'No such item exists.';
            return $result;
        }
        $result['item'] = $item;
        return $result;
    }
}