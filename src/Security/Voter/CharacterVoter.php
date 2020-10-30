<?php

namespace App\Security\Voter;

use App\Entity\Character;
use LogicException;
use PhpParser\Node\Stmt\If_;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterVoter extends Voter
{
    public const CHARACTER_CREATE = 'characterCreate';
    public const CHARACTER_DISPLAY = 'characterDisplay';

    private const ATTRIBUTES = array(
        self::CHARACTER_CREATE,
        self::CHARACTER_DISPLAY,
    );

    protected function supports($attribute, $subject)
    {
        if (null !== $subject){
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Defines access rights
        switch($attribute){
            case self::CHARACTER_CREATE:
                return $this->canCreate(); //$this->canCreate($token);
                break;
            case self::CHARACTER_DISPLAY:
                //Peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay(); //$this->canDisplay($token, $subject);
                break;
        }

        throw new LogicException('Invalid attribute : ' . $attribute);
    }

    /**
     * Checks if is allowed to display
     */
    private function canDisplay(){
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

    /**
     * Checks if is allowed to create
     */
    private function canCreate(){
        // IMP Usually checks to know if user is allowed are here
        return true;
    }
}
