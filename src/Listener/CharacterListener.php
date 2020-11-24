<?php

namespace App\Listener;

use App\Entity\Character;
use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DateTime;

class CharacterListener implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return array(CharacterEvent::CHARACTER_CREATED => 'characterCreated');
    }

    public function characterCreated($event)
    {
        $character = $event->getCharacter();

        $character->setIntelligence(250);

        $dateBegin = new DateTime('24-11-2020');
        $dateEnd = new DateTime('30-11-2020');
        $dateToday = new DateTime();

        if($dateToday > $dateBegin && $dateToday < $dateEnd){
            $character->setLife(150);
        }

    }
}