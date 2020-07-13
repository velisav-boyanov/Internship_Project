<?php


namespace Controller;


use Core\View;

class GameController
{
    public function start()
    {
        View::render('game');
    }
}