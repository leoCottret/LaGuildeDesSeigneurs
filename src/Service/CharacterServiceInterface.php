<?php

namespace App\Service;

use App\Entity\Character;

interface CharacterServiceInterface
{
    /**
     * Creates the character
     */
    public function create(string $data);
    public function delete(Character $character);
    /**
     * Checks if the entity has been well filled
     */
    public function isEntityFilled(Character $character);
    /**
     * Submit the data to hydrate the object
     * https://symfony.com/doc/current/form/direct_submit.html
     */
    public function submit(Character $character, $formName, $data);
    public function modify(Character $character, string $data);
    public function getAll();
}
