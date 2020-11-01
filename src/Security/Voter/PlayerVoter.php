<?php

namespace App\Security\Voter;

use App\Entity\Player;
use LogicException;
use PhpParser\Node\Stmt\If_;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerVoter extends Voter
{
    public const CHARACTER_CREATE = 'playerCreate';

    private const ATTRIBUTES = array(
        self::CHARACTER_CREATE,
    );

    protected function supports($attribute, $subject)
    {
        if (null !== $subject){
            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
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
        }

        throw new LogicException('Invalid attribute : ' . $attribute);
    }

    /**
     * Checks if is allowed to create
     */
    private function canCreate(){
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

}
