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
    public const CHARACTER_DELETE = 'characterDelete';
    public const CHARACTER_MODIFY = 'characterModify';
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_DISPLAY_MORE_INTELLIGENT_THAN = 'characterDisplayMoreIntelligentThan';
    public const CHARACTER_INDEX = 'characterIndex';

    private const ATTRIBUTES = array(
        self::CHARACTER_DELETE,
        self::CHARACTER_CREATE,
        self::CHARACTER_MODIFY,
        self::CHARACTER_DISPLAY,
        self::CHARACTER_INDEX,
        self::CHARACTER_DISPLAY_MORE_INTELLIGENT_THAN,
    );

    protected function supports($attribute, $subject)
    {
        if (null !== $subject) {
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Defines access rights
        switch ($attribute) {
            case self::CHARACTER_CREATE:
                return $this->canCreate(); //$this->canCreate($token);
                break;
            case self::CHARACTER_DELETE:
                return $this->canDelete(); //$this->canDelete($token);
                break;
            case self::CHARACTER_MODIFY:
                return $this->canModify(); //$this->canModify($token);
                break;
            case self::CHARACTER_DISPLAY:
            case self::CHARACTER_DISPLAY_MORE_INTELLIGENT_THAN: // someone that can display all characters can also display the more intelligent than number
            case self::CHARACTER_INDEX:
                //Peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay(); //$this->canDisplay($token, $subject);
                break;
        }

        throw new LogicException('Invalid attribute : ' . $attribute);
    }

    /**
     * Checks if is allowed to display
     */
    private function canDisplay()
    {
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

    /**
     * Checks if is allowed to create
     */
    private function canCreate()
    {
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

    /**
     * Checks if is allowed to modify
     */
    private function canModify()
    {
        // IMP Usually checks to know if user is allowed are here
        return true;
    }

    /**
     * Checks if is allowed to delete
     */
    private function canDelete()
    {
        // IMP Usually checks to know if user is allowed are here
        return true;
    }
}
