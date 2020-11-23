<?php

namespace App\Service;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates the player
     */
    public function create(string $data);
    public function delete(Player $player);
    /**
     * Checks if the entity has been well filled
     */
    public function isEntityFilled(Player $character);
    /**
     * Submit the data to hydrate the object
     * https://symfony.com/doc/current/form/direct_submit.html
     */
    public function submit(Player $character, $formName, $data);
    public function modify(Player $player, string $data);
    public function getAll();
}