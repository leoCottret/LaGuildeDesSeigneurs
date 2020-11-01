<?php

namespace App\Service;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates the player
     */
    public function create();
    public function delete(Player $player);
    public function modify(Player $player);
    public function getAll();
}