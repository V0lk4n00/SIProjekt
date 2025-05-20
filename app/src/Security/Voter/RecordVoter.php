<?php

/**
 * Record voter.
 */

namespace App\Security\Voter;

use App\Entity\Record;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RecordVoter.
 */
class RecordVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'VIEW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * OrderVoter constructor.
     *
     * Security helper.
     */
    private AuthorizationCheckerInterface $auth;


    /**
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(AuthorizationCheckerInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Record;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->auth->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                /* @var $user User */
                return $this->canEdit($subject, $user);
            case self::VIEW:
                /* @var $user User */
                return $this->canView($subject, $user);
            case self::DELETE:
                /* @var $user User */
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * Checks if user can edit record.
     *
     * @param Record $record Record entity
     * @param User   $user   User
     *
     * @return bool Result
     */
    private function canEdit(Record $record, User $user): bool
    {
        return $record->getAuthor() === $user;
    }

    /**
     * Checks if user can view record.
     *
     * @param Record $record Record entity
     * @param User   $user   User
     *
     * @return bool Result
     */
    private function canView(Record $record, User $user): bool
    {
        return $record->getAuthor() === $user;
    }

    /**
     * Checks if user can delete record.
     *
     * @param Record $record Record entity
     * @param User   $user   User
     *
     * @return bool Result
     */
    private function canDelete(Record $record, User $user): bool
    {
        return $record->getAuthor() === $user;
    }
}
