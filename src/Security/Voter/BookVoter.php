<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    private const UPDATE = 'update';
    private const DELETE = 'delete';

    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::UPDATE, self::DELETE], true)
            && $subject instanceof Book;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Book $book */
        $book = $subject;

        switch ($attribute) {
            case self::UPDATE:
                return $this->canUpdate($book, $user);
                break;
            case self::DELETE:
                return $this->canDelete($book, $user);
                break;
        }

        return false;
    }

    private function canUpdate(Book $book, User $user): bool
    {
        return $book->getCreatedBy() === $user;
    }

    private function canDelete(Book $book, User $user): bool
    {
        return $book->getCreatedBy() === $user;
    }
}
