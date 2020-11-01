<?php

namespace App\Security\Voter;

use App\Entity\Player;
use LogicException;
use PhpParser\Node\Stmt\If_;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerVoter extends Voter
{
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_DELETE = 'playerDelete';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_INDEX = 'playerIndex';

    private const ATTRIBUTES = array(
        self::PLAYER_DELETE,
        self::PLAYER_CREATE,
        self::PLAYER_MODIFY,
        self::PLAYER_DISPLAY,
        self::PLAYER_INDEX,
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
            case self::PLAYER_CREATE:
                return $this->canCreate(); //$this->canCreate($token);
                break;
            case self::PLAYER_DELETE:
                return $this->canDelete(); //$this->canDelete($token);
                break;
            case self::PLAYER_MODIFY:
                return $this->canModify(); //$this->canModify($token);
                break;
            case self::PLAYER_DISPLAY:
            case self::PLAYER_INDEX:
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

    /**
     * Checks if is allowed to modify
     */
    private function canModify(){
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

    /**
     * Checks if is allowed to delete
     */
    private function canDelete(){
        // IMP Usually checks to know if user is allowed are here
        return true;
    }
}
